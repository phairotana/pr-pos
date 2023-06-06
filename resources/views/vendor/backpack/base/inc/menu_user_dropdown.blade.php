<li class="nav-item dropdown pr-4 pl-4">
    <a class="nav-link text-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
        aria-expanded="false">
        <img class="avatar_medium" src="{{ backpack_user()->MediumProfile }}">
        <span>{{ ucfirst(Auth::user()->name) }}</span>
        <em class="la la-caret-down"></em>
    </a>
    <div
        class="dropdown-menu {{ config('backpack.base.html_direction') == 'rtl' ? 'dropdown-menu-left' : 'dropdown-menu-right' }} mr-4 pb-1 pt-1">
        <a class="dropdown-item" href="{{ route('backpack.account.info') }}"><i class="la la-user"></i>
            {{ trans('backpack::base.my_account') }}</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ backpack_url('logout') }}"><i class="la la-lock"></i>
            {{ trans('backpack::base.logout') }}</a>
    </div>
</li>
