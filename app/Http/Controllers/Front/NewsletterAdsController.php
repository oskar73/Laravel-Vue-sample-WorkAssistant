<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Integration\Cart;
use App\Models\NewsletterAdsEvent;
use App\Models\NewsletterAdsPosition;
use App\Models\NewsletterAdsPrice;
use Illuminate\Http\Request;
use Validator;
use Session;

class NewsletterAdsController extends Controller
{
    public $model;

    public function __construct(NewsletterAdsPosition $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $result = $this->model->filterItem($request);

            return response()->json($result);
        }

        return view('frontend.newsletterAds.index');
    }

    public function position(Request $request, $slug)
    {
        $position = $this->model->where('slug', $slug)->firstOrFail();

        if ($request->ajax()) {
            $start = $request->start;
            $end = $request->end;

            $events = NewsletterAdsEvent::join("newsletter_ads_listings", "newsletter_ads_events.listing_id", "newsletter_ads_listings.id")
                ->where(function ($q) use ($start, $end) {
                    $q->whereBetween("newsletter_ads_events.start_date", [$start, $end]);
                    $q->orWhereBetween("newsletter_ads_events.end_date", [$start, $end]);
                })
                ->where("newsletter_ads_listings.position_id", $position->id)
                ->select("newsletter_ads_events.id", "newsletter_ads_events.start_date", "newsletter_ads_events.end_date")
                ->get();
            $lists = [];
            foreach ($events as $event) {
                if ($event->start_date != null && $event->end_date != null) {
                    $lists[] = [
                        'id' => 'e' . $event->id,
                        'start' => $event->start_date . " 00:00:00",
                        'end' => $event->end_date . " 24:00:00",
                        'color' => '#ff0000',
                        'rendering' => 'background',
                        'allDay' => true,
                    ];
                }
            }

            return $lists;
        }

        return view('frontend.newsletterAds.position', compact("position"));
    }

    public function addToCart(Request $request, $id)
    {
        try {
            $position = $this->model->where('id', $id)
                ->where('status', 1)
                ->firstorfail();

            if (!$request->price) {
                return response()->json([
                    'status' => 0,
                    'errors' => ['Select the price option'],
                ]);
            }

            $price = NewsletterAdsPrice::where('id', $request->price)
                ->where('position_id', $id)
                ->where('status', 1)
                ->firstorfail();

            $validation = Validator::make($request->all(), $position->addToCartRule($price), $this->model::ADDTOCART_VALIDATION_MESSAGE);

            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'errors' => $validation->errors()->all(),
                ]);
            }

            if ($price->type === 'impression') {
                $itemPrice = $price->price;
                $parameter = $price->impression;
            } else {
                $itemPrice = count($request->start) * $price->price;
                $parameter['start'] = $request->start;
                $parameter['end'] = $request->end;
            }

            $position->price = $price;

            $oldCart = Session::get("cart");
            $cart = new Cart($oldCart);
            $cart->add($position, route('newsletterAds.position', $position->slug), 1, $itemPrice, 'newsletterAds', $position->getFirstMediaUrl("thumbnail"), 0, $position->name, $parameter);

            Session::put("cart", $cart);

            return response()->json([
                'status' => 1,
                'data' => [
                    'cart' => $cart,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
