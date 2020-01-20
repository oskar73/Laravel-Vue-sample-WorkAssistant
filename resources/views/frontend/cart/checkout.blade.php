@extends('layouts.app')

@section('title', 'Cart Checkout')

@section('style')
@endsection
@section('content')
    <section>
        <div class="container">
            <div class="back-link mb-3">
                <a href="{{route('cart.index')}}" class="hover-none">
                    <i class="fa fa-chevron-left" aria-hidden="true"></i> Back to Cart
                </a>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <h2>{{$policy->title}}</h2>
                    <div class="pr-3 custom-scroll-h policy_area white-space-pre-line">
                      {!! $policy->body !!}
                    </div>
                    <hr>
                    <div class="mt-3">
                        <label for="agree_policy">
                            <input type="checkbox" id="agree_policy">
                            I agree with the policy.
                        </label>
                    </div>

                    <div class="payment_gateway mb-5 d-none @guest @if(session("paypalguestemail")==null)freezen @endif @endguest">
                        @guest
                            <form action="{{route('cart.checkEmail')}}" method="POST" id="guest_email_form">
                                @csrf
                                @honeypot
                                <div class="alert" role="alert">
                                    If you already have account, please <a href="{{route('cart.login')}}?redirect={{route('cart.checkout')}}" class="cart_login text-primary">login</a> first.
                                    If you don't have account yet, type email here. <br> We will create your account and send to this email with the credentials.

                                    <div class="d-flex">
                                        <div class="d-inline-block mb-2">
                                            <input type="email" name="guest_email" class="fcustom-input" autocomplete="off" id="guest_email" value="{{session("paypalguestemail")}}">
                                            <div class="form-control-feedback error-guest_email"></div>
                                        </div>
                                        <div class="d-inline-block ml-2 mt-1">
                                            <button class="btn btn-outline-success confirmBtn" type="submit">Confirm</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endguest

                        <div id="accordion" class="accordion-style2">
                            @if(count($gateway)==0)
                                <div>Sorry, admin didn't set payment yet</div>
                            @endif
                            @if(in_array('paypal', $gateway))
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <img src="{{asset('/assets/img/paypal.png')}}" alt="" class="h-22px">
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="card-body">

                                        <form action="{{route('cart.paypal.getUrl')}}" method="POST" id="paypal_submit_form">
                                            @csrf
                                            @honeypot
                                            <input type="hidden" name="guest_email" class="guest_email_input">
                                            <button type="button" class="btn btn-primary btn-block mb-3 submit_btn paypal_smt_btn">
                                                <i class="fab fa-paypal"></i> Pay with Paypal</button>
                                        </form>
                                        <div class="freezen_div"><span>Please fill the email form first.</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(in_array('stripe', $gateway))
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <img src="{{asset('/assets/img/cards.png')}}" alt="" class="h-22px">
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                    <div class="card-body stripe_area">
                                        <main>
                                            <section class="container-lg">
                                                <div class="cell example example4" id="example-4">
                                                    <form id="stripe_smt_form">
                                                        @csrf
                                                        @honeypot

                                                        <input type="hidden" name="token" class="stripe_token">
                                                        <div id="example4-paymentRequest">

                                                        </div>
                                                        <fieldset>
                                                            <legend class="card-only" data-tid="elements_examples.form.pay_with_card">Pay with card</legend>
                                                            <legend class="payment-request-available" data-tid="elements_examples.form.enter_card_manually">Or enter card details</legend>

                                                            <div class="container mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-6 custom_left_stripe_el">
                                                                        <input class="form-control stripe_custom_input" type="text" name="name" placeholder="Name" required>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input class="form-control stripe_custom_input" type="email" name="email" placeholder="Email Address" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-8 custom_left_stripe_el">
                                                                        <input class="form-control stripe_custom_input" type="text" name="address" placeholder="Address" required>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input class="form-control stripe_custom_input" type="text" name="country" placeholder="Country" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container mb-2">
                                                                <div class="row">
                                                                    <div class="col-md-6 custom_left_stripe_el">
                                                                        <input class="form-control stripe_custom_input" type="text" name="city" placeholder="City" required>
                                                                    </div>
                                                                    <div class="col-md-3 custom_left_stripe_el">
                                                                        <input class="form-control stripe_custom_input" type="text" name="state" placeholder="State" required>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        <input class="form-control stripe_custom_input" type="text" name="zipcode" placeholder="ZIP" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="container">
                                                                <div id="example4-card"></div>
                                                                <button type="submit" data-tid="elements_examples.form.donate_button">Pay now</button>
                                                            </div>
                                                        </fieldset>
                                                        <div class="error" role="alert">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                                                <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                                                <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                                                            </svg>
                                                            <span class="message"></span>
                                                        </div>
                                                    </form>
                                                    <div class="success">
                                                        <div class="icon">
                                                            <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                                                <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                                                            </svg>
                                                        </div>
                                                        <h3 class="title" data-tid="elements_examples.success.title">Payment successful</h3>
                                                        <p class="message"><span data-tid="elements_examples.success.message">Thanks for your payment: </span></p>
                                                        <a class="reset" href="#">
                                                            <svg width="32px" height="32px" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                <path fill="#000000" d="M15,7.05492878 C10.5000495,7.55237307 7,11.3674463 7,16 C7,20.9705627 11.0294373,25 16,25 C20.9705627,25 25,20.9705627 25,16 C25,15.3627484 24.4834055,14.8461538 23.8461538,14.8461538 C23.2089022,14.8461538 22.6923077,15.3627484 22.6923077,16 C22.6923077,19.6960595 19.6960595,22.6923077 16,22.6923077 C12.3039405,22.6923077 9.30769231,19.6960595 9.30769231,16 C9.30769231,12.3039405 12.3039405,9.30769231 16,9.30769231 L16,12.0841673 C16,12.1800431 16.0275652,12.2738974 16.0794108,12.354546 C16.2287368,12.5868311 16.5380938,12.6540826 16.7703788,12.5047565 L22.3457501,8.92058924 L22.3457501,8.92058924 C22.4060014,8.88185624 22.4572275,8.83063012 22.4959605,8.7703788 C22.6452866,8.53809377 22.5780351,8.22873685 22.3457501,8.07941076 L22.3457501,8.07941076 L16.7703788,4.49524351 C16.6897301,4.44339794 16.5958758,4.41583275 16.5,4.41583275 C16.2238576,4.41583275 16,4.63969037 16,4.91583275 L16,7 L15,7 L15,7.05492878 Z M16,32 C7.163444,32 0,24.836556 0,16 C0,7.163444 7.163444,0 16,0 C24.836556,0 32,7.163444 32,16 C32,24.836556 24.836556,32 16,32 Z"></path>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </section>
                                        </main>
                                        <div class="freezen_div"><span>Please fill the email form first.</span></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="checkout-cart">
                        <h3>Your Cart</h3>
                        <ul class="product-list">
                            @foreach($cart->items as $item)
                            <li>
                                <div class="pl-thumb">
                                    <img src="{{$item['image']}}" alt="{{$item['front']}}" class="w-80px">
                                </div>
                                <h6>
                                    {{$item['front']}}
                                </h6>
                                <p>
                                    @if($item['type']=='blogAds'&&$item['item']['price']['type']=='period')
                                        Period:<br>
                                        @foreach($item['parameter']['start'] as $key2=>$start)
                                            <span class="font-size14">{{$start}} ~ {{$item['parameter']['end'][$key2]}}</span> <br>
                                        @endforeach
                                    @else
                                        Quantity: {{$item['quantity']}}
                                    @endif
                                </p>
                                <p>
                                   Price: ${{$item['price']}} @if($item['recurrent']==1) / {{periodName($item['parameter']['period'], $item['parameter']['period_unit'])}}
                                    <br>(Subscription) @endif
                                </p>
                            </li>
                            <div class="clearfix"></div>
                             <hr>
                            @endforeach
                        </ul>
                        <ul class="price-list">
                            <li>
                                Onetime Total
                                <span>${{$cart->onetimeTotalPrice}}</span>
                            </li>
                            <li>
                                Subscription Total
                                <span>${{$cart->subTotalPrice}}</span>
                            </li>
                            <li class="total">
                                Total
                                <span>${{$cart->totalPrice}}</span>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-5">
                        @if(Session::has("selected_template"))
                           Picked Website Template:  <br>
                            <a href="{{route('template.view', Session::get("selected_template")->slug)}}" target="_blank" class="link" style="max-height:200px;overflow:hidden;display:block;">
                                <img src="{{Session::get("template")['template']->getFirstMediaUrl("preview")}}" alt="">
                            </a>
                            <div class="text-center">{{Session::get("template")['template']->name}}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script>
        $(".paypal_smt_btn").on("click", function(e) {
            e.preventDefault();
            $(".guest_email_input").val($("#guest_email").val());
            $(".paypal_smt_btn").append("<i class='fa fa-spin fa-spinner ml-3'></i>").prop("disabled", true);
            $("#paypal_submit_form").submit();
        });
        $("#agree_policy").on("click", function(e) {
            if($(this).is(':checked'))
            {
                $(".payment_gateway").removeClass("d-none");
            }else {
                $(".payment_gateway").addClass("d-none");
            }
        })
        $("#guest_email_form").submit(function(event) {
            event.preventDefault();
            if($("#guest_email").val()=='') return itoastr("error", "Please input email");

            $(".confirmBtn").append(" <i class='fa fa-spinner fa-spin loading_div'></i>").attr("disabled", true);

            $.ajax({
                url:$(this).attr("action"),
                method: 'POST',
                data: new FormData(this),
                dataType:'JSON',
                contentType:false,
                cache:false,
                processData:false,
                success: function(result)
                {
                    $(".confirmBtn").prop("disabled", false);
                    $(".loading_div").remove();

                    console.log(result)
                    if(result.status===0)
                    {
                        dispErrors(result.data)
                        dispValidErrors(result.data)
                    }else {
                        itoastr('success', 'Success!');
                        $(".payment_gateway").removeClass("freezen");
                    }
                },
                error: function(e)
                {
                    console.log(e)
                }
            });
        });

    </script>
    @if(in_array('stripe', $gateway))
        <script src="https://js.stripe.com/v3/"></script>
        <script> var stripe_pk = "{{$stripe_pk}}", total="{{$cart->totalPrice}}"</script>
        <script src="{{asset('assets/js/front/cart/checkout.js')}}"></script>
    @endif
@endsection
