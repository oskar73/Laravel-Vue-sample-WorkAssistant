<?php

namespace App\Http\Controllers\User;

use App\Models\Newsletter;

class NewsletterController extends UserController
{

    public function __construct(Newsletter $model)
    {
        $this->model = $model;
    }

    public function archive()
    {
        if (request()->wantsJson()) {
            $items = Newsletter::where('status', 'sent')->select('name', 'description', 'slug', 'subject', 'sent_at')->latest()->get();

            $html = view('components.user.newsletterArchiveTable', [
                'items' => $items,
                'selector' => 'database-all'
            ])->render();

            return response()->json([
                'status' => 1,
                'html' => $html,
                'count' => count($items),
            ]);
        }

        return view('user.newsletter.archive');
    }
}
