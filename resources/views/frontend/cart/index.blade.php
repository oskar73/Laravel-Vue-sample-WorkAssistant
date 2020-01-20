@extends('layouts.app')

@section('title', 'Cart')

@section('style')
    <style>
        .pm_label.active {
            background-color: #fff;
            box-shadow: 1px 0 3px 0 #3333;
        }
    </style>
@endsection
@section('content')
    <section class="mt-120px">
        <div class="bg-gray">
            <div class="container">
                <div class="d-flex justify-content-between py-3">
                    <h3 class="mb-0 fw-600 text-black">Cart</h3>
                </div>
            </div>
        </div>
        <div class="bg-light-gray py-5">
            <div class="container">
                <div class="table-responsive">
                    <table class="table table-item-center table-borderless">
                        <thead>
                        <tr>
                            <td class="text-left">Image</td>
                            <td class="text-left">Name</td>
                            <td>Purchase Type</td>
                            <td>Quantity</td>
                            <td>Price</td>
                            <td>Total amount</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody class="cart_item_area">
                        <tr><td colspan="6" class="text-center"><i class="loading_div fas fa-spinner fa-spin fa-fw fa-3x"></i></td></tr>
                        </tbody>
                    </table>
                </div>

                <hr/>

                @if (request('action') == 'login')
                <div class="tw-text-center tw-py-12 tw-text-lg">
                    <p class="text-danger">
                        Success! Please check your mailbox to obtain your credentials to login.
                    </p>
                    <p class="tw-text-sm tw-mt-6">
                        ***Note: If you did not receive your email- please give it about 5 minutes then check your junk email box as well.
                    </p>
                    <p class="tw-text-sm">
                        If you still have not received an email please <a class="contact-btn tw-underline tw-text-blue-500 tw-cursor-pointer">contact</a> support.
                    </p>
                    <p class="tw-text-sm">Received the email? Great! Login <a class="tw-underline tw-text-blue-500" href="{{ route('ssoLogin') }}">here</a>.</p>
                </div>
                @elseif($cart && $cart->totalPrice!=0)
                    <div class="row">
                        <div class="col-md-6 offset-md-3">
                            <p class="fw-700 mb-2 mt-3">Payment method</p>
                            @if(count($gateway)==0)
                                <div>Sorry, admin didn't set payment yet</div>
                            @endif
                            @if(in_array('paypal', $gateway))
                                <label for="pm_paypal" class="h-cursor p-2 w-100 mb-1 d-flex justify-content-between pm_label active">
                                    <div>
                                        <input type="radio" name="payment_method" value="paypal" id="pm_paypal" checked> Paypal
                                    </div>
                                    <img src="{{asset("assets/img/paypal.png")}}" alt="" class="w-100px d-inline-block">
                                </label>
                            @endif
                            @if(in_array('stripe', $gateway))
                                <label for="pm_card" class="h-cursor p-2 w-100 mb-0 d-flex justify-content-between pm_label">
                                    <div>
                                        <input type="radio" name="payment_method" value="card" id="pm_card"> Credit Card
                                    </div>
                                    <img src="{{asset("assets/img/cards.png")}}" alt="" class="d-inline-block h-20px">
                                </label>
                            @endif
                            @guest
                                @if(session()->get("paypalguestemail", null) === null)
                                    <form action="{{route('cart.checkEmail')}}" method="POST" id="guest_email_form">
                                        @csrf
                                        @honeypot
                                        <div class="guest_area mt-4 alert alert-info border-radius-0">
                                            <p class="">
                                                To continue to payment and to create a new account, enter your email. If you already have an account, <a href="{{ route('ssoLogin') }}" class="highlight underline">LOGIN</a>
                                            </p>
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="email" name="guest_email" id="guest_email" class="form-control border-radius-0" placeholder="Email Address" value="{{session("paypalguestemail")}}">
                                                        <div class="form-control-feedback error-guest_email"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button class="btn btn-success border-radius-0 shadow confirmBtn tw-bg-green-600" type="submit">Confirm</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            @endguest
                            <div class="mt-3">
                                <div class="container mb-2">
                                    <div class="row">
                                        @if(isset($cart->coupon))
                                            <div class="col-md-12 text-center">
                                                Copoun {{ $cart->coupon->code }} has been applied with a {{ $cart->coupon->discount }}% discount.
                                            </div>
                                        @else
                                            <div class="col-md-6">
                                                <input class="form-control stripe_custom_input" id='has_coupon' type="checkbox" name="has_coupon">
                                                <label for="has_coupon" style="margin: 0 !important">I have a coupon code</label>
                                            </div>
                                            <div class="input-group mb-3 tw-mt-2 tw-px-4 d-none" id="coupon_area">
                                                <input class="form-control" id='coupon' type="text" name="coupon" placeholder="Enter Coupon Code">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="redeem_coupon">Redeem</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <hr/>
                            </div>
                            <div class="mt-5 bg-white pt-4 pb-3 px-3 small_shadow position-relative checkout_form"  @if(session()->get("paypalguestemail", null) === null)   @endif>
                                <div class="row">
                                    <div class="col-8">
                                        <p class="font-size20"><b>Total amount</b></p>
                                    </div>
                                    <div class="col-4">
                                        <p id="price" class="font-size20 text-right"><b class="c_total_price">${{formatNumber(session("cart")->totalPrice?? 0)}}</b></p>
                                    </div>
                                </div>
                                <form action="{{route('cart.paypal.getUrl')}}" method="POST" id="paypal_submit_form">
                                    @csrf
                                    @honeypot
                                    <input type="hidden" name="guest_email" class="guest_email_input">
                                </form>
                                <div class="stripe_area" style="display:none;">
                                    <div class="cell example example4 bg-white" id="example-4">
                                        <form id="stripe_smt_form">
                                            @csrf
                                            @honeypot
                                            <input type="hidden" name="token" class="stripe_token">
                                            <div id="example4-paymentRequest"> </div>

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
                                            </div>

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
                                </div>
                                <div id="formAction">
                                    <hr>
                                    <p>By continuing I accept the <a href="/term-of-use" class="highlight underline" target="_blank">Terms of use.</a></p>
                                    <button type="submit" class="btn theme style-1 mt-3 confirm_pay_btn w-100 py-2" id="pay_btn">
                                        <span>
                                            <h3 class="position-relative z-index-999">Confirm and Pay</h3>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@section('script')
    @if($cart && $cart->totalPrice!=0)
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            stripe_pk = "{{$stripe_pk}}";
            total="{{$cart->totalPrice}}";
        </script>
        <script src="{{asset('assets/js/front/cart/checkout.js')}}"></script>
    @endif
    <script>
        const isLoggedIn = Boolean('{{auth()->check()}}')
        const isGuest = {{ session()->get("paypalguestemail", null) === null }};
        if (isGuest && !isLoggedIn) {
            document.getElementById('formAction').classList.add('d-none')
        }
    </script>
    <script src="{{asset('assets/js/front/cart/index.js')}}"></script>
@endsection
