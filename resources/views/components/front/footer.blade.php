
<footer>
    <div class="container">
        <div class="row">

            <div class="col-lg-4 col-md-6 sm-margin-30px-bottom">
                <div class="w-100 mt-4">
                    <a href="/" class="footer-logo">
                        <img src="{{asset("assets/img/logo.png")}}" alt="">
                    </a>
                    <form action="{{route('subscribe')}}" class="newsletter_form" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="newsletter_email" class="text-white">Subscribe to Newsletter</label>
                            <div class="input-group">
                                <input type="email" class="form-control" name="newsletter_email" id="newsletter_email" placeholder="Email Address" required>
                                <button type="submit" class="btn subscribe_btn" style="padding: 0!important;">Subscribe</button>
                            </div>
                            <div class="form-control-feedback error-newsletter_email"></div>
                        </div>
                        <p>Join the newsletter to get notification.</p>
                    </form>
                </div>
                <div class="d-flex my-4">
                    <a href="https://social.bizinabox.com/@Bizinabox" class="d-block" target="_blank"><img src="{{asset('assets/img/social/bizinabox.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.linkedin.com/company/bizinaboxcom/" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/linkedin.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.pinterest.com/Bizinabox/" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/pinterest.svg')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.reddit.com/user/bizinabox/" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/reddit.svg')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.tiktok.com/@bizinaboxcom" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/tiktok.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.facebook.com/bizinaboxcom" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/facebook.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://twitter.com/Bizinaboxcom" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/twitter.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.instagram.com/Bizinaboxcom" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/instagram.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.threads.net/@bizinaboxcom" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/threads.png')}}" alt="" class="tw-w-7"></a>
                    <a href="https://www.youtube.com/@Bizinabox" class="d-block ml-2" target="_blank"><img src="{{asset('assets/img/social/youtube.png')}}" alt="" class="tw-w-7"></a>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 sm-margin-30px-bottom">
                <h3 class="text-theme">Services</h3>
                <ul class="footer-list">
                    <li><a href="{{route('blog.index')}}">Blog</a></li>
                    <li><a href="{{route('blogAds.index')}}">Blog Advertisement</a></li>
                    <li><a href="{{route('blog.package')}}">Guest blogging</a></li>
                    <li><a href="https://social.bizinabox.com">Community</a></li>
                    <li><a href="https://community.bizinabox.com/forum">Forum</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6 sm-margin-30px-bottom">
                <h3 class="text-theme">Company</h3>
                <ul class="footer-list">
                    <li><a href="/about">About</a></li>
                    <li class="d-none"><a href="/portfolio">Portfolio</a></li>
                    <li><a href="/legal/terms-and-conditions">Terms and Conditions</a></li>
                    <li><a href="/legal/privacy-policy">Privacy Policy</a></li>
                    <li><a href="/legal/disclaimer">Disclaimer</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-6 sm-margin-30px-bottom">
                <h3 class="text-theme">Help Center</h3>
                <ul class="footer-list">
                    <template class="d-none">
                        @isWhitelisted
                        <li><a href="#" onclick="window.open('/livechat', 'LiveChat', 'width=572, height=759')">Live Chat Support</a></li>
                        @endisWhitelisted
                        <li><a href="{{route('faq.index')}}">FAQ</a></li>
                    </template>
                    <li><a href="{{route('contact')}}">Contact Us</a></li>
                </ul>
            </div>
        </div>
        <div style="height:80px"></div>
    </div>
</footer>
