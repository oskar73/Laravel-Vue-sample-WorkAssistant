<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Bizinabox</title>
    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    @vite(['resources/sass/front.scss'])
    <link href='https://fonts.googleapis.com/css?family=Oswald:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900italic,900' rel='stylesheet' type='text/css'>
    <link rel="preload" href="{{asset('assets/img/login.jpg')}}" as="image" type="image/jpg" />
    <style>
        body {
            width: 100vw;
            min-height: 100vh;
            background-image: url({{asset('assets/img/login.jpg')}});
            background-repeat: no-repeat;
            background-size: cover;
            background-position: bottom right;
            font-family: Oswald;
            color: white;
            padding: 120px 0;
        }

        h1 {
            font-size: 68px;
        }

        .notice-box{
            box-shadow: 0 0 4px 2px #00000023;
            background-color: #00000008;
            padding: 20px;
            min-width: 80%;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="d-flex w-100 h-100">
    <div class="container m-auto">
        <div class="row align-items-stretch">
            <div class="col-lg-6">
                <h2>We are</h2>
                <h1>Coming Soon</h1>
                <p style="max-width: 600px; font-weight: 100">
                    Get ready we have something coming that is going to bellow you away.
                    Bellow is a sneak peek of what we offer, check back often for our launch.
                </p>

                <ul style="font-size: 24px">
                    <li>Bizinabox Blog</li>
                    <li>Free Logo Maker</li>
                    <li>Email Signature</li>
                    <li>Business Directory</li>
                    <li>Portfolio</li>
                    <li>Product Showcase</li>
                    <li>Social Community</li>
                    <li>Business News</li>
                    <li>Advertisement</li>
                </ul>

                <div class="d-flex align-items-center">
                    <a href="/auth/login" class="btn btn-success">Login</a>
                    <a  href="/auth/regiser"  class="btn btn-success ml-2">Become a customer</a>
                </div>
            </div>
            <div class="col-lg-6 d-flex justify-content-end align-items-center mt-4 mt-lg-0">
                <div class="notice-box">
                    <img src="{{asset('assets/img/default_logo.png')}}" />
                    <h1>Bizinabox</h1>
                    <div class="text-white-50" style="font-size: 20px; font-weight: 200">
                        <p class="mt-5">Professionally Designed Website Templates</p>
                        <p>
                            Design and build your own high-quality websites. Whether you’re promoting your business,
                            showcasing your work, opening your store or starting a blog—you can do it all with the Wix website builder.
                        </p>
                        <p>
                            Pick a template and customize anything, or answer a few questions and get a free website designed just for you.
                        </p>
                        <p>
                            Start your own blog, add an online store and accept bookings online. You can always add more features as you grow.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/dev1/both.js')}}"></script>
<script src="{{asset('assets/js/dev1/front.js')}}"></script>
<x-front.livechat></x-front.livechat>
</body>
</html>
