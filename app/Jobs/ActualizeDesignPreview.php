<?php

namespace App\Jobs;

use App\Models\GraphicDesign\UserDesign;
use App\Services\DesignService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActualizeDesignPreview implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use SerializesModels;
    use Queueable;

    protected UserDesign $userDesign;
    protected bool $create;

    public function __construct(UserDesign $userDesign, $create = false)
    {
        $this->onQueue('design-preview')->onConnection('database');
        $this->userDesign = $userDesign;
        $this->create = $create;
    }

    public function handle(DesignService $service)
    {
        // Get base64 logo preview
        $b64Data = $service->getDesignPreview($this->userDesign);

        // Actualize data
        $this->userDesign->preview()->updateOrCreate([
            'user_design_id' => $this->userDesign->id,
        ], [
            'content' => $b64Data,
        ]);
    }

    public function tags(): array
    {
        return ['actualize_design_preview', 'user_design_id:' . $this->userDesign->id];
    }
}