<?php

namespace App\Repositories;

use App\Jobs\ActualizeDesignPreview;
use App\Models\GraphicDesign\Graphic;
use App\Models\GraphicDesign\GraphicDesign;
use App\Models\GraphicDesign\UserDesign;
use App\Services\DesignService;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserDesignRepository extends BaseRepository
{
    public $model = UserDesign::class;

    public GraphicDesignRepository $designRepository;
    public DesignService $designService;
    protected Sanitizer $sanitizer;
    const TEMP_USER_DESIGN_HASH = 'temp_user_design_hash';

    public function __construct(GraphicDesignRepository $designRepository, Sanitizer $sanitizer, DesignService $designService)
    {
        parent::__construct();

        $this->designRepository = $designRepository;
        $this->sanitizer = $sanitizer;
        $this->designService = $designService;
    }

    public function getUserHash(GraphicDesign $design): string
    {
        return hash('sha256', $design->hash . time());
    }

    public function getSessionKey(string $hash): string
    {
        return self::TEMP_USER_DESIGN_HASH . $hash;
    }

    public function getSessionDesign($hash)
    {
        return session($this->getSessionKey($hash));
    }

    public function forgetSessionDesign($hash): void
    {
        session()->forget($this->getSessionKey($hash));
    }

    public function saveTempUserDesignToSession(UserDesign $userDesign): object
    {
        session()->put(self::getSessionKey($userDesign->hash), $userDesign);

        return $this;
    }

    public function createByDesign(GraphicDesign $design): UserDesign
    {
        return DB::transaction(function () use ($design) {
            $userHash = $this->getUserHash($design);

            // Save user hash to session
            $userDesign = new UserDesign([
                'graphic_id' => $design->graphic_id,
                'design_id' => $design->id,
                'hash' => $userHash,
                'design_content' => $design->content,
                'version' => "first_version",
            ]);

            $pairDesigns = [];
            foreach ($design->pairs as $pair) {
                $pairDesign = new UserDesign([
                    'graphic_id' => $pair->graphic_id,
                    'design_id' => $pair->id,
                    'hash' => $this->getUserHash($pair),
                    'design_content' => $pair->content,
                    'version' => "first_version",
                ]);
                $pairDesign->load('graphic');
                $pairDesigns[] = $pairDesign;
                $this->saveTempUserDesignToSession($pairDesign);
            }

            $userDesign->pairs = $pairDesigns;

            $this->saveTempUserDesignToSession($userDesign);

            return $userDesign;
        });
    }

    public function isEdited(UserDesign $userDesign): bool
    {
        return !((string)$userDesign->created_at === (string)$userDesign->updated_at);
    }

    protected function decodeDesign(string $design): string
    {
        return base64_decode(str_rot13($design));
    }

    public function synchronize(string $svgData, string $hash)
    {
        return DB::transaction(function () use ($svgData, $hash) {
            $userDesign = $this->first('hash', $hash);

            if (empty($userDesign)) {
                $userDesign = $this->getByHash($hash);
                $this->saveUserDesignFromGuestDesign($svgData, $userDesign, 'default');
            } else {
                // Encrypt Favicon
                $svgData = $this->decodeDesign($svgData);

                // Sanitize Favicon
                $sanitizedDesign = $this->sanitizer->sanitize($svgData);

                // Get some valid svg favicon
                $svgData = $sanitizedDesign ?? $svgData;

                $userDesign->update([
                    'design_content' => $svgData,
                ]);

                dispatch(new ActualizeDesignPreview($userDesign));
            }

            return $userDesign->fresh();
        });
    }

    public function syncGuestDesign(string $designSvg, string $hash): UserDesign
    {
        $userDesign = $this->getByHash($hash);

        $designSvg = $this->decodeDesign($designSvg);
        // Sanitize favicon
        $sanitizedDesign = $this->sanitizer->sanitize($designSvg);
        // Get some valid svg favicon
        $designSvg = $sanitizedDesign ?: $designSvg;
        $userDesign->design_content = $designSvg;
        $userDesign->version = $userDesign->version ?? 'default';

        session()->put([$this->getSessionKey($userDesign->hash) => $userDesign]);

        return $userDesign;
    }

    public function saveUserDesignFromGuestDesign(string $svgData, UserDesign $userDesign, $version = 'default'): UserDesign
    {
        $svgData = $this->decodeDesign($svgData);
        // Sanitize design
        $sanitizedDesign = $this->sanitizer->sanitize($svgData);
        // Get some valid svg design
        $svgData = $sanitizedDesign ?? $svgData;
        $userDesign->design_content = $svgData;

        $userDesign->user_id = user()->id;
        $userDesign->version = $version;

        $pairDesigns = $userDesign->pairs ?? [];
        unset($userDesign->pairs);

        $userDesign->save();

        dispatch(new ActualizeDesignPreview($userDesign));

        $this->forgetSessionDesign($userDesign->hash);

        if (count($pairDesigns) > 0) {
            foreach ($pairDesigns as $pairDesign) {
                $pairDesign->user_id = user()->id;
                $pairDesign->version = $version;
                $pairDesign->parent_id = $userDesign->id;
                $pairDesign->save();

                dispatch(new ActualizeDesignPreview($pairDesign));

                $this->forgetSessionDesign($pairDesign->hash);
            }
        }

        return $userDesign;
    }

    public function createNewVersion(string $design, UserDesign $userDesign, string $version)
    {
        $userHash = hash('sha256', $userDesign->hash . time());
        // Encrypt Favicon
        $design = $this->decodeDesign($design);

        // Sanitize Favicon
        $sanitizedDesign = $this->sanitizer->sanitize($design);

        // Get some valid svg favicon
        $design = $sanitizedDesign ?: $design;

        $userDesign = UserDesign::create([
            'graphic_id' => $userDesign->graphic_id,
            'design_id' => $userDesign->design_id,
            'user_id' => user()->id,
            'hash' => $userHash,
            'design_content' => $design,
            'version' => $version,
        ]);

        dispatch(new ActualizeDesignPreview($userDesign, true));

        return $userDesign->hash;
    }

    public function getByHash($hash)
    {
        if (auth()->check()) {
            $userDesign = UserDesign::where("hash", $hash)->with('pairs.graphic')->first();
        }
        if (empty($userDesign)) {
            $userDesign = $this->getSessionDesign($hash);
        }

        return $userDesign;
    }

    public function getEditorData(string $designHash): array|bool
    {
        $data = [];

        $userDesign = $this->getByHash($designHash);
        if (empty($userDesign)) {
            return false;
        }

        $userDesign->load('graphic');
        $userDesign->load('design');

        $fontUrl = Storage::disk(GraphicDesign::STORAGE_DISK)->url('fonts/css/fonts.css');

        $graphics = Graphic::all();

        $masks = $userDesign->graphic->getMedia('masks')->toArray();
        $icons = $userDesign->graphic->getMedia('icons')->toArray();
        $images = $userDesign->graphic->getMedia('images')->toArray();

        $data['content'] = $userDesign->getEncryptedDesignContent();
        if ($userDesign->design) {
            $data['tutorial'] = $userDesign->design->tutorial->id;
        }

        $ownerHash = request()->get('ownerHash') ?? $userDesign->hash;
        $ownerDesign = $this->getByHash($ownerHash);
        $ownerDesign->load('graphic');

        $data['fontUrl'] = $fontUrl;
        $data['graphics'] = $graphics;
        $data['is_premium'] = $userDesign->design->premium;
        $data['hash'] = $userDesign->hash;
        $data['version'] = $userDesign->version;
        $data['preview'] = $userDesign->preview;
        $data['graphic'] = $userDesign->graphic;
        $data['masks'] = $masks;
        $data['icons'] = $icons;
        $data['images'] = $images;
        $data['ownerDesign'] = $ownerDesign;
        $data['pairs'] = $ownerDesign->pairs ?? $ownerDesign['pairs'] ?? [];

        return $data;
    }

    public function getLiveDesigns(iterable $loadedDesigns = []): array
    {
        $previews = [];

        $designs = $this->model->with('preview')
            ->where("user_id", user()->id)
            ->whereNotIn('hash', $loadedDesigns)
            ->latest()
            ->limit(6)
            ->get();

        foreach ($designs as $design) {
            $previews[$design->hash] = $design->preview->content;
        }

        return $previews;
    }
}
