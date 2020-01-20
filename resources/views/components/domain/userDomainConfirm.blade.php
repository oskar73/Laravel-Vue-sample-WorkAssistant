<div class="row">
    <div class="w-100 tw-max-w-3xl m-auto">
        <div class="confirm_detail">
            <h4>Domain Name: <b>{{$domain}}</b> </h4><br>
            <h4>Duration: <b>{{$duration}} Years</b></h4><br>

            @if($tldRecord->WhoisVerification=='true')
                <span class="c-badge c-badge-success">Free WhoisGuard</span>  <br><br>
            @endif
            <label class="m-checkbox m-checkbox--state-success mt-2">
                <input type="checkbox" name="registerBiz" id="registerBiz" checked> register this domain for Bizinabox website.
                <i class="fa fa-info-circle tooltip_3" title="If you check this, default cname of domain will point to Bizinabox server. <br>If you don't check this field, you will be able to use this domain for other purpose."></i>
                <span></span>
            </label>
            <br>
            <br>

            <p class="fs-20">Total Price: <b>${{formatNumber($total)}}</b></p>

            @if($discount!=0)
                <p class="fs-16">Discount Price(-): <b>${{formatNumber(($total-$discount)<=0?$total:$discount)}}</b> (by {{$package->getName()}})</p>
                <hr>
                <p class="fs-20">Grand Total: <b>${{formatNumber(($total-$discount)<=0?0:$total-$discount)}}</b></p>
            @endif

            <br>
            @if($total-$discount<=0)
                <button type="button" class="btn btn-success getNowBtn">Get It For Free</button>
            @else
                <div class="m-accordion m-accordion--default" id="m_accordion_1" role="tablist">
                    @if(count($gateway)==0)
                        <div>Sorry, admin didn't set payment yet</div>
                    @endif
                    @if(in_array('paypal', $gateway))
                        <!--begin::Item-->
                        <div class="m-accordion__item">
                            <div class="m-accordion__item-head collapsed" role="tab" id="m_accordion_1_item_1_head" data-toggle="collapse" href="#m_accordion_1_item_1_body" aria-expanded="    false">
                                <img src="{{asset('assets/img/paypal.png')}}" alt="" class="h-22px">
                            </div>
                            <div class="m-accordion__item-body collapse" id="m_accordion_1_item_1_body" role="tabpanel" aria-labelledby="m_accordion_1_item_1_head" data-parent="#m_accordion_1">
                                <div class="m-accordion__item-content">
                                    <form action="{{route('user.domain.paywithPaypal')}}" method="POST" id="paypal_submit_form">
                                        @csrf
                                        @honeypot
                                        <input type="hidden" name="dns" id="dns_value" value="0">
                                        <button type="button" class="btn btn-primary btn-block mb-3 submit_btn paypal_smt_btn">
                                            <i class="fab fa-paypal"></i> Pay with Paypal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!--end::Item-->

                    @if(in_array('stripe', $gateway))
                        <!--begin::Item-->
                        <div class="m-accordion__item">
                            <div class="m-accordion__item-head collapsed" role="tab" id="m_accordion_1_item_2_head" data-toggle="collapse" href="#m_accordion_1_item_2_body" aria-expanded="    false">
                                <img src="{{asset('assets/img/cards.png')}}" alt="" class="h-22px">
                            </div>
                            <div class="m-accordion__item-body collapse" id="m_accordion_1_item_2_body" role="tabpanel" aria-labelledby="m_accordion_1_item_2_head" data-parent="#m_accordion_1">
                                <div class="m-accordion__item-content stripe_area">
                                    <main>
                                        <section class="container-lg">
                                            <div class="cell example example4" id="example-4">
                                                <form id="stripe_smt_form" class="m-auto">
                                                    @csrf
                                                    @honeypot

                                                    <input type="hidden" name="token" class="stripe_token">
                                                    <div id="example4-paymentRequest"></div>
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
                                </div>
                            </div>
                        </div>
                        <!--end::Item-->
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    var stripe_pk = "{{$stripe_pk}}", total="{{(($total-$discount)<=0?0:($total-$discount))*100}}";

    $(".paypal_smt_btn").on("click", function(e) {
        e.preventDefault();
        $(".paypal_smt_btn").append("<i class='fa fa-spin fa-spinner ml-3'></i>").prop("disabled", true);
        if($("#registerBiz").prop("checked"))
        {
            $("#dns_value").val(1);
        }else {
            $("#dns_value").val(0);
        }
        $("#paypal_submit_form").submit();
    });

</script>
@if($total-$discount>0&&in_array('stripe', $gateway))
<script>
    var stripe = Stripe(stripe_pk);

    function registerElements(elements, exampleName) {
        var formClass = '.' + exampleName;

        var example = document.querySelector(formClass);

        var form = example.querySelector('#stripe_smt_form');
        var resetButton = example.querySelector('a.reset');
        var error = form.querySelector('.error');
        var errorMessage = error.querySelector('.message');

        function enableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email'], input[type='tel']"
                ),
                function(input) {
                    input.removeAttribute('disabled');
                }
            );
        }

        function disableInputs() {
            Array.prototype.forEach.call(
                form.querySelectorAll(
                    "input[type='text'], input[type='email'], input[type='tel']"
                ),
                function(input) {
                    input.setAttribute('disabled', 'true');
                }
            );
        }

        function triggerBrowserValidation() {
            // The only way to trigger HTML5 form validation UI is to fake a user submit
            // event.
            var submit = document.createElement('input');
            submit.type = 'submit';
            submit.style.display = 'none';
            form.appendChild(submit);
            submit.click();
            submit.remove();
        }

        // Listen for errors from each Element, and show error messages in the UI.
        var savedErrors = {};
        elements.forEach(function(element, idx) {
            element.on('change', function(event) {
                if (event.error) {
                    error.classList.add('visible');
                    savedErrors[idx] = event.error.message;
                    errorMessage.innerText = event.error.message;
                } else {
                    savedErrors[idx] = null;

                    // Loop over the saved errors and find the first one, if any.
                    var nextError = Object.keys(savedErrors)
                        .sort()
                        .reduce(function(maybeFoundError, key) {
                            return maybeFoundError || savedErrors[key];
                        }, null);

                    if (nextError) {
                        // Now that they've fixed the current error, show another one.
                        errorMessage.innerText = nextError;
                    } else {
                        // The user fixed the last error; no more errors.
                        error.classList.remove('visible');
                    }
                }
            });
        });

        // Listen on the form's 'submit' handler...
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Trigger HTML5 validation UI on the form if any of the inputs fail
            // validation.
            var plainInputsValid = true;
            Array.prototype.forEach.call(form.querySelectorAll('input'), function(
                input
            ) {
                if (input.checkValidity && !input.checkValidity()) {
                    plainInputsValid = false;
                    return;
                }
            });
            if (!plainInputsValid) {
                triggerBrowserValidation();
                return;
            }

            // Show a loading screen...
            example.classList.add('submitting');

            // Disable all inputs.
            disableInputs();

            // Gather additional customer data we may have collected in our form.
            var name = form.querySelector('#' + exampleName + '-name');
            var address1 = form.querySelector('#' + exampleName + '-address');
            var city = form.querySelector('#' + exampleName + '-city');
            var state = form.querySelector('#' + exampleName + '-state');
            var zip = form.querySelector('#' + exampleName + '-zip');

            var additionalData = {
                name: name ? name.value : undefined,
                address_line1: address1 ? address1.value : undefined,
                address_city: city ? city.value : undefined,
                address_state: state ? state.value : undefined,
                address_zip: zip ? zip.value : undefined,
            };

            // Use Stripe.js to create a token. We only need to pass in one Element
            // from the Element group in order to create a token. We can also pass
            // in the additional customer data we collected in our form.
            stripe.createToken(elements[0], additionalData).then(function(result) {
                // Stop loading!


                if (result.token) {
                    // If we received a token, show the token ID.

                    example.querySelector('.stripe_token').value = result.token.id;

                    stripeFormSubmit();

                } else {
                    // Otherwise, un-disable inputs.
                    example.classList.remove('submitting');
                    enableInputs();
                }
            });
        });

        resetButton.addEventListener('click', function(e) {
            e.preventDefault();
            // Resetting the form (instead of setting the value to `''` for each input)
            // helps us clear webkit autofill styles.
            form.reset();

            // Clear each Element.
            elements.forEach(function(element) {
                element.clear();
            });

            // Reset error state as well.
            error.classList.remove('visible');

            // Resetting the form does not un-disable inputs, so we need to do it separately:
            enableInputs();
            example.classList.remove('submitted');
        });

        function stripeFormSubmit() {
            var formData = new FormData(document.querySelector('#stripe_smt_form'));
            formData.append("name", $("#stripe_smt_form input[name=name]").val());
            formData.append("email", $("#stripe_smt_form input[name=email]").val());
            formData.append("address", $("#stripe_smt_form input[name=address]").val());
            formData.append("country", $("#stripe_smt_form input[name=country]").val());
            formData.append("city", $("#stripe_smt_form input[name=city]").val());
            formData.append("state", $("#stripe_smt_form input[name=state]").val());
            formData.append("zipcode", $("#stripe_smt_form input[name=zipcode]").val());
            formData.append("guest_email", $("#guest_email").val());
            formData.append("dns", $("#registerBiz").prop("checked")===true?1:0);

            $.ajax({
                url:"/account/domain/paywithStripe",
                method:"POST",
                data:formData,
                dataType:'JSON',
                contentType:false,
                cache:false,
                processData:false,
                success:function(result)
                {
                    enableInputs();
                    example.classList.remove('submitting');

                    if(result.status==1)
                    {
                        example.classList.add('submitted');
                        window.setTimeout(function() {
                            window.location.href="/account/dashboard"
                        }, 1000)
                    }else if(result.status==0)
                    {
                        error.classList.add('visible');
                        savedErrors[0] = result.data;
                        errorMessage.innerText= result.data;

                    }else if(result.status==2){
                        dispErrors(result.data)
                    }
                }

            })
        }
    }

    (function() {
        "use strict";

        var elements = stripe.elements({
            fonts: [
                {
                    cssSrc: "https://rsms.me/inter/inter-ui.css"
                }
            ],
            // Stripe's examples are localized to specific languages, but if
            // you wish to have Elements automatically detect your user's locale,
            // use `locale: 'auto'` instead.
            locale: window.__exampleLocale
        });

        /**
         * Card Element
         */
        var card = elements.create("card", {
            style: {
                base: {
                    color: "#32325D",
                    fontWeight: 500,
                    fontFamily: "Inter UI, Open Sans, Segoe UI, sans-serif",
                    fontSize: "16px",
                    fontSmoothing: "antialiased",

                    "::placeholder": {
                        color: "#CFD7DF"
                    }
                },
                invalid: {
                    color: "#E25950"
                }
            }
        });

        card.mount("#example4-card");

        /**
         * Payment Request Element
         */
        var paymentRequest = stripe.paymentRequest({
            country: "US",
            currency: "usd",
            total: {
                amount: parseFloat(total),
                label: "Total"
            }
        });
        paymentRequest.on("token", function(result) {
            var example = document.querySelector(".example4");
            example.querySelector(".token").innerText = result.token.id;

            example.classList.add("submitted");
            result.complete("success");
        });

        var paymentRequestElement = elements.create("paymentRequestButton", {
            paymentRequest: paymentRequest,
            style: {
                paymentRequestButton: {
                    type: "donate"
                }
            }
        });

        paymentRequest.canMakePayment().then(function(result) {
            if (result) {
                document.querySelector(".example4 .card-only").style.display = "none";
                document.querySelector(
                    ".example4 .payment-request-available"
                ).style.display =
                    "block";
                paymentRequestElement.mount("#example4-paymentRequest");
            }
        });

        registerElements([card, paymentRequestElement], "example4");
    })();

</script>
@endif
