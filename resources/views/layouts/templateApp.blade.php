<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{$template->name}} - Template | Bizinabox</title>

    <link rel="icon" href="{{asset('assets/img/favicon.ico')}}" />

    <meta name="keywords" content="HTML5 Template Crizal" />
    <meta name="description" content="Latest updates and statistic charts">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="canonical" href="{{ config('app.url') }}/templates/{{ $template->slug }}">
    <link rel="stylesheet" href="{{mix('assets/resources/css/style.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/template/preview.css')}}" />
</head>
<body style="overflow: hidden">
<div class="main-wrapper">
    <header>
        <div class="navbar-default">
            <!-- end top search -->
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 col-lg-12">
                        <div class="menu_area alt-font">
                            <nav class="navbar navbar-expand-lg navbar-light no-padding">

                                <div class="navbar-header navbar-header-custom p-2 ml-lg-5 pl-lg-5 ">
                                    <!-- start logo -->
                                    <a href="/templates">❮ Back</a>
                                    <!-- end logo -->

                                    <div class="handle_switch_area">
                                        <a href="javascrpit:void(0);" data-hook="desktop_view" class="view_switch_btn active">
                                            <svg width="28" height="22" viewBox="0 0 28 22" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="#000" fill-rule="evenodd">
                                                    <path d="M11 18h1v4h-1z"></path>
                                                    <path d="M9 21h10v1H9z"></path>
                                                    <path d="M16 18h1v4h-1z"></path>
                                                    <path d="M1 3v13c0 1.11.891 2 1.996 2h22.008A2.004 2.004 0 0 0 27 16V3c0-1.11-.891-2-1.996-2H2.996A2.004 2.004 0 0 0 1 3zM0 3c0-1.657 1.35-3 2.996-3h22.008A2.994 2.994 0 0 1 28 3v13c0 1.657-1.35 3-2.996 3H2.996A2.994 2.994 0 0 1 0 16V3z"></path>
                                                </g>
                                            </svg>
                                        </a>
                                        <a href="javascrpit:void(0);" data-hook="pad_view" class="view_switch_btn">
                                            <svg width="20" height="28" viewBox="0 0 20 28" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="#000" fill-rule="evenodd">
                                                    <path d="M1 2.996v22.008C1 26.1 1.897 27 2.994 27h14.012c1.1 0 1.994-.895 1.994-1.996V2.996A2.001 2.001 0 0 0 17.006 1H2.994C1.894 1 1 1.895 1 2.996zm-1 0A2.997 2.997 0 0 1 2.994 0h14.012A3.001 3.001 0 0 1 20 2.996v22.008A2.997 2.997 0 0 1 17.006 28H2.994A3.001 3.001 0 0 1 0 25.004V2.996z"></path>
                                                    <path d="M9 23h2v2H9z"></path>
                                                </g>
                                            </svg>
                                        </a>
                                        <a href="javascrpit:void(0);" data-hook="mobile_view" class="view_switch_btn">
                                            <svg width="12" height="22" viewBox="0 0 12 22" xmlns="http://www.w3.org/2000/svg">
                                                <g fill="#000" fill-rule="evenodd">
                                                    <path d="M1 3.001V19C1 20.105 1.894 21 2.997 21h6.006A2 2 0 0 0 11 18.999V3A1.999 1.999 0 0 0 9.003 1H2.997A2 2 0 0 0 1 3.001zm-1 0A3 3 0 0 1 2.997 0h6.006A2.999 2.999 0 0 1 12 3.001V19A3 3 0 0 1 9.003 22H2.997A2.999 2.999 0 0 1 0 18.999V3z"></path>
                                                    <path d="M5 18h2v2H5z"></path>
                                                </g>
                                            </svg>
                                        </a>
                                    </div>
                                </div>


                                <ul class="ml-auto" id="nav">
                                    <li >
                                        <a href="{{route('template.view', $template->slug)}}" class="btn m-btn--sm m-btn--hover-info" target="_blank">View demo site <i class="fa fa-external-link-alt"></i></a>
                                    </li>
                                </ul>
                                <!-- end menu area -->

                                <!-- start attribute navigation -->
                                <div class="attr-nav sm-no-margin ">
                                    <ul>
                                        <li >
                                            <a href="{{route('template.start', $template->slug)}}"  class="btn m-btn--sm m-btn--hover-info" >Start with this template</a>
                                        </li>
                                    </ul>
                                </div>
                                <!-- end attribute navigation -->

                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="template_demo_area">
        <div class="desktop_view_area parent_iframe_area">
            <div class="mobile_top_cover device_frame_area">
                <div class="mobile_top_cover_line"></div>
            </div>
            <iframe src="{{route('template.view', $template->slug)}}" width="100%" height="100%" class="desktop_iframe preview_iframe"></iframe>
            <div class="mobile_bottom_cover device_frame_area"></div>
        </div>
    </div>
</div>

<script src="{{mix('assets/resources/js/script.js')}}"></script>

<script>
    var demo = "{{route('template.view', $template->slug)}}";
    var parameter = '';

    $(".view_switch_btn").click(function() {
        $(".parent_iframe_area").removeClass().addClass('parent_iframe_area').addClass($(this).data("hook")+"_area");
    });

    function updateFrame()
    {
        $(".preview_iframe").prop("src", demo+parameter);
    }

</script>
</body>

</html>
