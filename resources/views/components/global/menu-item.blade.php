@props([
    'menu' => null
])
@if($menu && !($menu['disabled']??false))
    <li class="m-menu__item {{isset($menu['url']) && Request::is($menu['url']) ? ('m-menu__item--active '.(($menu['hasSub']??false)?'m-menu__item--open':'')) : '' }} m-menu__item--submenu"
        aria-haspopup="true" m-menu-submenu-toggle="hover" m-menu-link-redirect="1"
        {!! isset($menu['intro']) ? $menu['intro']: null !!}
    >
        <a href="{{isset($menu['href'])?$menu['href']:(($menu['route']??false)?route($menu['route']):'javascript:void(0);')}}"
           class="m-menu__link m-menu__toggle" target="{{isset($menu['blank']) ? '_blank' : ''}}"
        >
            <span class="m-menu__item-here"></span>
            @if($menu['svg']??false)
                <i class="m-menu__link-icon">
                    <img src="{{asset('assets/img/'.$menu['svg'].'.svg')}}" alt="dashboard">
                </i>
            @else
                <i class="m-menu__link-icon {{$menu['icon']??'flaticon-layers'}}"></i>
            @endif
            <span class="m-menu__link-text">{{$menu['title']}}</span>
            @if($menu['hasSub']??false)
                <i class="m-menu__ver-arrow la la-angle-right"></i>
            @endif
            @if($menu['hasCount']??false)
                <span class="m-badge m-badge--danger mr-1 sidebar_todo_count" style="display:none;"></span>
            @endif
        </a>
        @if($menu['hasSub']??false)
            <div class="m-menu__submenu ">
                <span class="m-menu__arrow"></span>
                <ul class="m-menu__subnav">
                    @foreach($menu['subMenu'] as $subMenu)
                        @if(!($subMenu['disabled']??false))
                            <li class="m-menu__item {{ Request::is($subMenu['url']) ? 'm-menu__item--active '.(($subMenu['hasSub']??false) ? 'm-menu__item--open' :'') : '' }}"
                                aria-haspopup="true"
                            >
                                <a href="{{($subMenu['route']??false)?route($subMenu['route'], ($subMenu['params']??[])):'javascript:void(0);'}}"
                                   class="m-menu__link {{($subMenu['hasSub']??false) ? 'm-menu__toggle' :''}}"
                                >
                                    @if($subMenu['svg']??false)
                                        <i class="m-menu__link-icon">
                                            <img src="{{asset('assets/img/'.$subMenu['svg'].'.svg')}}" alt="dashboard">
                                        </i>
                                    @else
                                        <i class="m-menu__link-bullet {{$subMenu['icon']??'m-menu__link-bullet--dot'}}"><span></span></i>
                                    @endif
                                    <span class="m-menu__link-text">{{$subMenu['title']}}</span>
                                </a>
                                @if($subMenu['hasSub']??false)
                                    <div class="m-menu__submenu ">
                                        <span class="m-menu__arrow"></span>
                                        <ul class="m-menu__subnav">
                                            @foreach($subMenu['subMenu'] as $subMenu2)
                                                @if(!($subMenu2['disabled']??false))
                                                    <li class="m-menu__item {{ Request::is($subMenu2['url']) ? 'm-menu__item--active' : '' }}"
                                                        aria-haspopup="true"
                                                    >
                                                        <a href="{{($subMenu2['route']??false)?route($subMenu2['route'], ($subMenu2['params']??[])):'javascript:void(0);'}}"
                                                           class="m-menu__link "
                                                        >
                                                            @if($subMenu2['svg']??false)
                                                                <i class="m-menu__link-icon">
                                                                    <img src="{{asset('assets/img/'.$subMenu2['svg'].'.svg')}}"
                                                                         alt="dashboard"
                                                                    >
                                                                </i>
                                                            @else
                                                                <i class="m-menu__link-bullet {{$subMenu2['icon']??'m-menu__link-bullet--dot'}}"><span></span></i>
                                                            @endif
                                                            <span class="m-menu__link-text">{{$subMenu2['title']}}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        @endif
        @if($menu['divider']??false)
            <hr />
        @endif
    </li>
@endif
