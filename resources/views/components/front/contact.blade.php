<figure data-href="#" class="page-top-info progressive img-bg-effect-container text-center">
    <img src="https://images.pexels.com/photos/3781524/pexels-photo-3781524.jpeg" alt="bizinabox home page portfolio" class="img-bg"/>
</figure>

<div class="container contact-form py-5">
    <div class="section-heading text-center mb-5 w-100">
        <h3>Contact Us</h3>
        <p class="w-55px sm-w-75px xs-w-95px">Bizinabox has created some of the best tools for your small business that you may use to help your business thrive!</p>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="w-100 h-100">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26360763.088299938!2d-113.74592522530561!3d36.242734688809676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x54eab584e432360b%3A0x1c3bb99243deb742!2sUnited%20States!5e0!3m2!1sen!2sin!4v1646654860691!5m2!1sen!2sin"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        <div class="col-lg-6">
            <form action="{{route('contact.sendmail')}}" method="POST">@csrf
                <div class="mb-3">
                    <input type="text" class="form-control rounded-0" placeholder="Name" name="name" value="{{ old('name') }}">
                    @if ($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control rounded-0" placeholder="Email Address" name="email" value="{{ old('email') }}">
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="mb-3">
                    <textarea name="message" id="" cols="30" rows="5" class="form-control rounded-0" placeholder="Message">{{ old('message') }}</textarea>
                    @if ($errors->has('message'))
                        <span class="text-danger">{{ $errors->first('message') }}</span>
                    @endif
                </div>
                @if(config('captcha.sitekey'))
                    <div class="biz_captcha_container">
                        {!! NoCaptcha::display() !!}
                    </div>
                @endif
                @if ($errors->has('g-recaptcha-response'))
                    <div class="form-control-feedback error-g-recaptcha-response">{{ $errors->first('g-recaptcha-response') }}</div>
                @endif
                <button type="submit" class="btn btn-success rounded-0 tw-bg-green-600">Submit</button>
            </form>
        </div>
    </div>
</div>

{{-- <div class="bz-front-page--home">
    <section>
        <div class="container">
            <div class="row feature-boxes-container">
                <div class="col-lg-4 col-md-12 sm-margin-20px-bottom feature-box-04">
                    <div class="feature-box-inner h-100"><i class="icon-layers font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i> <h4
                            class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">Address</h4>
                        <div class="sepratar"></div>
                        <p>132, My Street, Kingston, New York 12401</p></div>
                </div>
                <div class="col-lg-4 col-md-12 sm-margin-20px-bottom feature-box-04">
                    <div class="feature-box-inner h-100"><i class="icon-genius font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i> <h4
                            class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">Email</h4>
                        <div class="sepratar"></div>
                        <p>info@bizinabox.com</p></div>
                </div>
                <div class="col-lg-4 col-md-12 feature-box-04">
                    <div class="feature-box-inner h-100"><i class="icon-hotairballoon font-size50 md-font-size46 sm-font-size40 xs-font-size38"></i> <h4
                            class="font-size15 xs-font-size14 margin-10px-top xs-margin-8px-top text-uppercase fw-600 text-black">Contact No</h4>
                        <div class="sepratar"></div>
                        <p>+18143511228</p></div>
                </div>
            </div>
        </div>
    </section>
</div> --}}
