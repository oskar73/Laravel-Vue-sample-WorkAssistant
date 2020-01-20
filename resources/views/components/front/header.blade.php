<header class="bz-front-component--header">
    <div class="navbar navbar-expand-md">
        <div class="collapse navbar-collapse mx-w-1350px m-auto" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item"><a class="nav-link c-link {{Request::is('/') ? 'active': ''}}"
                                        href="{{route('home')}}">Home</a></li>
                <li class="nav-item"><a class="nav-link c-link {{Request::is('blog') ? 'active': ''}}"
                                        href="{{route('blog.index')}}">Blog</a></li>
                <li class="nav-item"><a class="nav-link c-link" href="https://social.bizinabox.com">Community</a></li>
                <li class="nav-item"><a class="nav-link c-link" href="https://community.bizinabox.com/forum">Forum</a>
                </li>
                <li class="nav-item"><a class="nav-link c-link {{Request::is('blogAds*') ? 'active': ''}}"
                                        href="{{ route('blogAds.index') }}">Advertise</a></li>
                <li class="nav-item"><a class="nav-link c-link {{Request::is('contact') ? 'active': ''}}"
                                        href="{{ route('contact') }}">Contact Us</a></li>
                {{-- @if(getenv('app_env') == 'local')
                    <li class="nav-item"><a class="nav-link c-link {{Request::is('portfolio*') ? 'active': ''}}" href="{{route('portfolio.index')}}">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link c-link {{Request::is('templates*') ? 'active': ''}}" href="{{route('template.index')}}">Templates</a></li>
                    <li class="nav-item"><a class="nav-link c-link {{Request::is('package*') ? 'active': ''}}" href="{{route('package.index')}}">Package</a></li>
                    <li class="nav-item"><a class="nav-link c-link {{Request::is('readymade*') ? 'active': ''}}" href="{{route('readymade.index')}}">Ready Made BIZ</a></li>

                    <div x-cloak class="tw-relative tw-inline-block tw-text-left" x-data="{open: false}" x-outside-click="open = false">
                        <div class="tw-h-full tw-flex tw-items-center tw-text-white" style="font-family: Roboto; font-weight: 300;font-size: 15px;">
                            <button type="button" class="tw-flex tw-items-center tw-justify-center tw-gap-x-1.5 tw-rounded-md tw-px-3" @click="open = true">
                                Modules
                                <svg fill="#ffffff" height="8px" width="8px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 407.437 407.437" xml:space="preserve">
                                    <polygon points="386.258,91.567 203.718,273.512 21.179,91.567 0,112.815 203.718,315.87 407.437,112.815 "/>
                                </svg>
                            </button>
                        </div>
                        <div x-show="open" class="tw-absolute tw-right-0 tw-z-10 tw-mt-2 tw-w-40 tw-origin-top-right tw-divide-y tw-divide-gray-100 tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-focus:outline-none">
                            <div class="py-1" role="none">
                                <a href="{{ route('service.index') }}" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"  style="font-family: Roboto; font-weight: 300;font-size: 15px;">Services</a>
                            </div>
                            <div class="py-1" role="none">
                                <a href="{{ route('plugin.index') }}" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"  style="font-family: Roboto; font-weight: 300;font-size: 15px;">Plugins</a>
                            </div>
                            <div class="py-1" role="none">
                                <a href="{{ route('lacarte.index') }}" class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"  style="font-family: Roboto; font-weight: 300;font-size: 15px;">A La Carte</a>
                            </div>
                        </div>
                    </div>
                    <li class="nav-item"><a class="nav-link c-link {{Request::is('directory*') ? 'active': ''}}" href="{{route('directory.index')}}">Directory</a></li>
                @endif --}}
                <div x-cloak class="tw-relative tw-inline-block tw-text-left" x-data="{open: false}"
                     x-outside-click="open = false">
                    <div class="tw-h-full tw-flex tw-items-center tw-text-white"
                         style="font-family: Roboto; font-weight: 300;font-size: 15px;">
                        <button type="button"
                                class="tw-flex tw-items-center tw-justify-center tw-gap-x-1.5 tw-rounded-md tw-px-3"
                                @click="open = true" style="font-family: Roboto; font-weight: 300;font-size: 15px;">
                            Graphic Designs
                            <svg fill="#ffffff" height="8px" width="8px" version="1.1" id="Layer_1"
                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 407.437 407.437" xml:space="preserve">
                                    <polygon
                                            points="386.258,91.567 203.718,273.512 21.179,91.567 0,112.815 203.718,315.87 407.437,112.815 " />
                                </svg>
                        </button>
                    </div>
                    <div x-show="open"
                         class="tw-absolute tw-right-0 tw-z-10 tw-mt-2 tw-w-40 tw-origin-top-right tw-divide-y tw-divide-gray-100 tw-rounded-md tw-bg-white tw-shadow-lg tw-ring-1 tw-ring-black tw-ring-opacity-5 tw-focus:outline-none">
                        @foreach(\App\Models\GraphicDesign\Graphic::all() as $category)
                            <div class="py-1" role="none">
                                <a href="{{ route('graphics.category', $category->slug) }}"
                                   class="tw-text-gray-700 tw-block tw-px-4 tw-py-2 tw-text-sm"
                                   style="font-family: Roboto; font-weight: 300;font-size: 15px;">
                                    {{ $category->title }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </ul>
            <ul class="user-cart-item ml-auto">
                <li class="nav-item d-flex align-items-center pl-2">
                    <a href="{{route('cart.index')}}" class="position-relative hover-none nav-link">
                        <svg style="cursor: pointer;" height="24px" width="24px" fill="#ffffff"
                             xmlns="http://www.w3.org/2000/svg" id="mdi-cart" viewBox="0 0 24 24">
                            <path d="M17,18C15.89,18 15,18.89 15,20A2,2 0 0,0 17,22A2,2 0 0,0 19,20C19,18.89 18.1,18 17,18M1,2V4H3L6.6,11.59L5.24,14.04C5.09,14.32 5,14.65 5,15A2,2 0 0,0 7,17H19V15H7.42A0.25,0.25 0 0,1 7.17,14.75C7.17,14.7 7.18,14.66 7.2,14.63L8.1,13H15.55C16.3,13 16.96,12.58 17.3,11.97L20.88,5.5C20.95,5.34 21,5.17 21,5A1,1 0 0,0 20,4H5.21L4.27,2M7,18C5.89,18 5,18.89 5,20A2,2 0 0,0 7,22A2,2 0 0,0 9,20C9,18.89 8.1,18 7,18Z" />
                        </svg>
                        @if(session()->has("cart"))
                            <span class="cart_badge_btn">{{session("cart")->totalQty?? 0}}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <nav class="navbar">
        <div class="w-100 mx-w-1350px py-2 mx-auto d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{asset('assets/img/logo.png')}}" alt="" class="nav_logo_img">
            </a>
            <ul class="user-cart-item ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link account_btn" href="{{ route('ssoLogin') }}">
                            Log In
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a class="nav-link account_btn" href="{{ route('ssoRegister') }}">
                            SignUp
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link account_btn" href="{{route('dashboard')}}">
                            My Account
                        </a>
                    </li>
                @endguest
                <li class="nav-item d-block d-md-none">
                    <div class="navbar-toggler" type="button" data-toggle="collapse"
                         data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                         aria-expanded="false"
                         aria-label="{{ __('Toggle navigation') }}"
                    >
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>

@push('script')
    <script>
      $('.submenu-button').click(function() {
        $(this).parent().toggleClass('active')
      })
    </script>
@endpush
