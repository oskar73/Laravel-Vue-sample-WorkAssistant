<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\CampaignViewMail;
use App\Models\EmailCampaign;
use App\Models\EmailCategory;
use App\Models\LegalPage;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Storage;

class HomeController extends Controller
{
    public function hacker($slug)
    {
        \Log::info("hacking is started");
        \Log::info($slug);
    }

    public function index()
    {
        $data = option('home.how-to-build', []);

        if (config('app.coming-soon')) {
            return view('frontend.coming-soon', compact('data'));
        }

        return view('frontend.home', compact('data'));
    }

    public function legal($slug)
    {
        $page = LegalPage::whereSlug($slug)->firstorfail();

        return view("frontend.legal", compact("page"));
    }

    public function about()
    {
        $page = LegalPage::whereSlug('about')->firstorfail();
        $root = true;

        return view("frontend.legal", compact("page", "root"));
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'newsletter_email' => 'required|email|unique:subscribers,email,NULL,id,status,1',
        ]);
        $subscriber = new Subscriber();
        $subscriber->subscribe($request);

        return view("frontend.redirect", [
            "data" => '<div class="font-size20">Thank you for your subscription! <br><br> <p class="font-size16"> Almost done... Please confirm in your mail inbox or spam folder.</p></div>',
        ]);
    }

    public function subscribeConfirm($token)
    {
        $subscriber = Subscriber::whereToken($token)->first();
        if ($subscriber == null) {
            return redirect()->route('home')
                ->with("info", "Session is expired.");
        }
        if ($subscriber->status == 1) {
            return view("frontend.redirect", [
                "data" => '<div class="font-size20">You are already subscribed! <br><br> <p class="font-size14"> If you don\'t have account yet, you can create <a href="/register" class="forgot_psw">here.</a></p></div>',
            ]);
        }
        $subscriber->status = 1;
        $subscriber->save();

        return view("frontend.redirect", [
            "data" => '<div class="font-size20">Thank you for your subscription! <br><br> <p class="font-size14"> If you don\'t have account yet, you can create <a href="/register" class="forgot_psw">here.</a></p></div>',
        ]);
    }

    public function mail($id)
    {
        $campaign = EmailCampaign::where("status", 'sent')
            ->where("id", hashDecode($id))
            ->firstorfail();

        $data['body'] = $campaign->body;
        $data['subject'] = $campaign->subject;

        return new CampaignViewMail($data);
    }

    public function unsubscribe(Request $request)
    {
        if (!$request->hasValidSignature()) {
            return redirect()->route("home");
        }
        $categories = EmailCategory::status(1)->orderBy("order")->get(['id', 'name', 'slug']);

        return view("frontend.unsubscribe", compact("categories"));
    }

    public function unsubscribeConfirm(Request $request)
    {
        $subscriber = Subscriber::whereEmail($request->email)->first();
        if ($subscriber == null) {
            return back()->with('info', "You are not in subscriber list");
        }
        if ($request->all) {
            $subscriber->delete();
        } else {
            $subscriber->categories()->sync($request->categories);
        }

        return view("frontend.redirect", [
            "data" => 'Successfully Unsubscribed!',
        ]);
    }

    public function getImage($path)
    {
        try {
            $headers = [
                'Content-Type: application/image',
            ];

            if (Storage::disk('private')->exists($path)) {
                $file = Storage::disk('private')->path($path);

                return response()->file($file, $headers);
            } else if (Storage::disk('s3-pub-bizinabox')->exists($path)) {
                $file = Storage::disk('s3-pub-bizinabox')->path($path);

                return response()->file($file, $headers);
            }
            abort(404);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function unsubscribeNewsletter()
    {
        return view('frontend.newsletterUnsubscribe');
    }

    public function sitemap()
    {
        if (Storage::disk('s3-pub-bizinabox')->exists('static/sitemap.xml')) {
            $xml = Storage::disk('s3-pub-bizinabox')->get('static/sitemap.xml');

            return response($xml, 200)->header('Content-Type', 'application/xml');
        }
        abort(404);
    }
}
