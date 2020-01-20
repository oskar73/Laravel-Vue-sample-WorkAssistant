<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use App\Models\FaqItem;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public $model;

    public function __construct()
    {
        $this->model = new FaqItem();
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        $categories = FaqCategory::select('id', 'slug', 'name')
            ->with('approvedItems')
            ->status(1)
            ->orderBy('order')
            ->get();

        return view('frontend.faq.index', compact('categories'));
    }
    public function detail($slug)
    {
        $item = $this->model->where('slug', $slug)
            ->with('media')
            ->status(1)
            ->firstorfail();

        return view('frontend.faq.detail', compact('item'));
    }

    public function contact()
    {
        return view('frontend.contact.index');
    }

    public function contactSendMail(Request $request)
    {
        $rule = [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ];
        if (config('captcha.sitekey')) {
            $rule['g-recaptcha-response'] = 'required|captcha';
        }
        $request->validate($rule, [
            'g-recaptcha-response.*' => 'Please check this field.',
        ]);

        $input = $request->all();

        //  Send mail
        try {
            \Mail::send('frontend.contact.contactMail', [
                'name' => $input['name'],
                'email' => $input['email'],
                'subject' => $input['message'],
            ], function ($message) use ($request) {
                $message->from('contact@bizinabox.com');
                $message->to('admin@bizinabox.com', 'BizinaBox')->subject('BizinaBox Contact Mail');
            });

            return redirect()->back()->with(['success' => 'Contact Form Submit Successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['error' => $e]);
        }
    }
}
