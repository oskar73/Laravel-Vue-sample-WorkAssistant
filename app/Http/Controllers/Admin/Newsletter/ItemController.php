<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Http\Controllers\Admin\AdminController;
use App\Jobs\SendNewsletterJob;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\NewsletterAdsListing;
use App\Models\NewsletterAdsPosition;
use App\Models\NewsletterTemplate;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ItemController extends AdminController
{
    public function __construct(Newsletter $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (request()->wantsJson()) {
            $items = $this->model->select(['id', 'name', 'slug', 'subject', 'description', 'status', 'sent_at', 'failed', 'created_at'])
                ->latest()
                ->get();

            $draftItems = $items->where('status', 'draft');
            $archiveItems = $items->where('status', 'sent');
            $failedItems = $items->where('failed', '!=', null);

            $all = view('components.admin.newsletter.allItem', [
                'items' => $items,
                'selector' => "datatable-all",
            ])->render();

            $draft = view('components.admin.newsletter.draftItem', [
                'items' => $draftItems,
                'selector' => "datatable-draft",
            ])->render();

            $archive = view('components.admin.newsletter.sentItem', [
                'items' => $archiveItems,
                'selector' => "datatable-archive",
            ])->render();

            $failed = view('components.admin.newsletter.failedItem', [
                'items' => $failedItems,
                'selector' => "datatable-failed",
            ])->render();

            $count['all'] = $items->count();
            $count['draft'] = $draftItems->count();
            $count['archive'] = $archiveItems->count();
            $count['failed'] = $failedItems->count();

            return response()->json([
                'status' => 1,
                'all' => $all,
                'draft' => $draft,
                'archive' => $archive,
                'failed' => $failed,
                'count' => $count,
            ]);
        }
        $templates = NewsletterTemplate::all();

        return view(self::$viewDir . 'newsletter.item', compact('templates'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'subject' => 'required|string',
                'description' => 'nullable|string',
            ]);

            $data = $request->all();
            $item = new Newsletter();
            $item->name = $data['name'];
            $item->subject = $data['subject'];
            $item->description = $data['description'];

            if ($data['template']) {
                $template = NewsletterTemplate::find($data['template']);

                if (!$template) {
                    return $this->jsonError(['Template not found']);
                }

                $item->html = $template->html;
                $item->modelData = $template->modelData;
            }
            $item->save();

            return response()->json([
                'status' => 1,
                'redirect' => route('admin.newsletter.item.design', ['slug' => $item->slug]),
            ]);
        } catch (\Exception $e) {
            return $this->jsonError([$e->getMessage()]);
        }
    }

    public function design($slug)
    {
        $item = $this->model->where('slug', $slug)->first();

        if (!$item) {
            return redirect()->route('admin.newsletter.item.index')->with('error', 'Item not found');
        }

        if ($item->status === 'sent') {
            return redirect()->route('admin.newsletter.item.index')->with('error', 'Item already sent');
        }

        return view(self::$viewDir . 'newsletter.itemDesign', compact('item'));
    }

    public function edit($slug)
    {
        $item = $this->model->where('slug', $slug)->first();

        if (!$item) {
            return redirect()->route('admin.newsletter.item.index')->with('error', 'Item not found');
        }

        if ($item->status === 'sent') {
            return redirect()->route('admin.newsletter.item.index')->with('error', 'Item already sent');
        }

        $templates = NewsletterTemplate::all();

        return view(self::$viewDir . 'newsletter.itemEdit', compact('item', 'templates'));
    }

    public function update($slug, $type, Request $request)
    {
        $item = $this->model->where('slug', $slug)->first();

        if (!$item) {
            return $this->jsonError(['Item not found']);
        }

        if ($item->status === 'sent') {
            return $this->jsonError(['Item already sent']);
        }

        if ($type === 'design') {
            $item->html = $this->replaceAds($request->html);
            $item->modelData = $request->model;
        } elseif ($type === 'details') {
            $item->name = $request->name;
            $item->subject = $request->subject;
            $item->description = $request->description;
        }

        $item->save();

        return response()->json([
            'status' => 1,
        ]);
    }

    public function preview($slug)
    {
        $item = $this->model->where('slug', $slug)->firstorfail();

        if (!$item->html) {
            return back()->with('error', 'Item content is empty');
        }

        return response($item->html)->header('Content-Type', 'text/html');
    }

    public function review($slug)
    {
        $item = $this->model->where('slug', $slug)->firstorfail();

        if ($item->status === 'sent') {
            return back()->with('error', 'Item already sent');
        }

        if (!$item->html) {
            return back()->with('error', 'Item content is empty');
        }

        return view(self::$viewDir . 'newsletter.itemReview', compact('item'));
    }

    public function test($slug, Request $request)
    {
        try {
            $item = $this->model->where('slug', $slug)->first();

            if (!$item) {
                return $this->jsonError(['Item not found']);
            }

            if ($item->status === 'sent') {
                return $this->jsonError(['Item already sent']);
            }

            $request->validate([
                'email' => 'required|email',
            ]);

            $subscriber = Subscriber::where('email', $request->email)->first();

            Mail::to($request->email)->send(new NewsletterMail($item, $subscriber));

            return response()->json([
                'status' => 1,
            ]);
        } catch (\Exception $e) {
            return $this->jsonError([$e->getMessage()]);
        }
    }

    public function send($slug)
    {
        try {
            $item = $this->model->where('slug', $slug)->first();

            if (!$item) {
                return $this->jsonError(['Item not found']);
            }

            if ($item->status === 'sent') {
                return $this->jsonError(['Item already sent']);
            }

            $this->dispatch(new SendNewsletterJob($item));

            return response()->json([
                'status' => 1,
            ]);
        } catch (\Exception $e) {
            return $this->jsonError([$e->getMessage()]);
        }
    }

    public function delete($slug)
    {
        $item = $this->model->where('slug', $slug)->first();

        if (!$item) {
            return $this->jsonError(['Item not found']);
        }

        $item->delete();

        return response()->json([
            'status' => 1,
        ]);
    }

    public function replaceAds($html)
    {
        $now = now();

        $ads = [
            [
                'id' => 1,
                'link_placeholder' => 'single_ad_link',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/singleAd.png',
            ],
            [
                'id' => 2,
                'link_placeholder' => 'double_ad_link_1',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/doubleAd1.png',
            ],
            [
                'id' => 3,
                'link_placeholder' => 'double_ad_link_2',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/doubleAd2.png',
            ],
            [
                'id' => 4,
                'link_placeholder' => 'triple_ad_link_1',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/tripleAd1.png',
            ],
            [
                'id' => 5,
                'link_placeholder' => 'triple_ad_link_2',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/tripleAd2.png',
            ],
            [
                'id' => 6,
                'link_placeholder' => 'triple_ad_link_3',
                'image_placeholder' => 'https://s3.amazonaws.com/storage.bizinabox.com/assets/img/tripleAd3.png',
            ],
        ];

        foreach ($ads as $ad) {
            if ((str_contains($html, $ad['link_placeholder'])) || (str_contains($html, $ad['image_placeholder']))) {
                $adListing = NewsletterAdsListing::where('position_id', $ad['id'])
                    ->where('status', 'approved')
                    ->whereHas('events', function ($query) use ($now) {
                        $query->where('start_date', '<=', $now)
                            ->where(function ($query) use ($now) {
                                $query->where('end_date', '>=', $now)
                                    ->orWhereNull('end_date');
                            });
                    })
                    ->first();

                if ($adListing) {
                    $html = str_replace($ad['link_placeholder'], route('newsletter.impClick', ['id' => $adListing->id]), $html);
                    $html = str_replace($ad['image_placeholder'], $adListing->getFirstMediaUrl('image'), $html);
                } else {
                    $position = NewsletterAdsPosition::find($ad['id']);
                    if ($position) {
                        $html = str_replace($ad['link_placeholder'], $position->default_url, $html);
                        $html = str_replace($ad['image_placeholder'], $position->getFirstMediaUrl('image'), $html);
                    }
                }
            }
        }


        return $html;
    }
}
