<div>
    <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
        <i class="la la-close"></i>
    </button>
    <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-light ">
        <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-light m-aside-menu--submenu-skin-light position-static" m-menu-vertical="1" m-menu-scrollable="1" m-menu-dropdown-timeout="500">
            <ul class="m-menu__nav ">
                @foreach(config('custom.menu.admin') as $menu)
                    <x-global.menu-item :menu="$menu" />
                @endforeach
               <li class="m-menu__item" aria-haspopup="true">
                    <a href="javascript:void(0);" class="m-menu__link" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="m-menu__link-icon fa fa-sign-out-alt"></i>
                        <span class="m-menu__link-text">Log out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
