<div class="header-content d-none d-lg-block">
  @hook('admin.header.before')
  <div class="header-wrap">
    <div class="header-left">
      @hook('admin.header.left.before')
      <div class="logo">
        <a href="{{ admin_route('home.index') }}"><img src="{{ asset(system_setting('base.admin_logo', 'image/logo.png')) }}" class="img-fluid"></a>
      </div>
      @hook('admin.header.left.after')
    </div>
    <div class="header-right">
      @hook('admin.header.right.before')

      <ul class="navbar navbar-right">
        @hook('admin.header.navbar.before')

        @hookwrapper('admin.header.user')
        <li class="nav-item me-3">
          <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <span class="ml-2">{{ current_user()->name }}</span>
            </a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
              <li>
                <a target="_blank" href="{{ shop_route('home.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-send me-1"></i> {{ __('admin/common.access_frontend') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('account.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-person-circle me-1"></i> {{ __('admin/common.account_index') }}
                </a>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a href="{{ admin_route('logout.index') }}" class="dropdown-item py-2">
                  <i class="bi bi-box-arrow-left me-1"></i> {{ __('common.sign_out') }}
                </a>
              </li>
            </ul>
          </div>
        </li>
        @endhookwrapper

        @hook('admin.header.navbar.after')
      </ul>

      @hook('admin.header.right.after')
    </div>
  </div>
  @hook('admin.header.after')
</div>

<div class="header-mobile d-lg-none">
  <div class="header-mobile-wrap">
    <div class="header-mobile-left">
      <div class="mobile-open-menu"><i class="bi bi-list"></i></div>
    </div>
    <div class="logo">
      <a href="{{ admin_route('home.index') }}"><img src="{{ asset(system_setting('base.admin_logo', 'image/logo.png')) }}" class="img-fluid"></a>
    </div>
    <div class="header-mobile-right">
      <div class="mobile-to-front">
        <a target="_blank" href="{{ shop_route('home.index') }}" class="nav-divnk"><i class="bi bi-send"></i></a>
      </div>
    </div>
  </div>
</div>

<div class="update-pop p-3" style="display: none">
  <div class="mb-4 fs-5 fw-bold text-center">{{ __('admin/common.update_title') }}</div>
  <div class="py-3 px-4 bg-light mx-3 lh-lg mb-4">
    <div>{{ __('admin/common.update_new_version') }}：<span class="new-version fs-5 fw-bold text-success"></span></div>
    <div>{{ __('admin/common.update_old_version') }}：<span class="fs-5">{{ config('beike.version') }}</span></div>
    <div>{{ __('admin/common.update_date') }}：<span class="update-date fs-5"></span></div>
  </div>

  <div class="d-flex justify-content-center mb-3">
    <button class="btn btn-outline-secondary me-3 ">{{ __('common.cancel') }}</button>
    <a href="https://beikeshop.com/download" target="_blank"
       class="btn btn-primary">{{ __('admin/common.update_btn') }}</a>
  </div>
</div>