<header>
  @hook('header.before')
  {{-- top currency/phone strip removed for cleaner flower shop UI --}}

  <div class="header-content d-none d-lg-block">
    <div class="container-fluid navbar-expand-lg">
      @hookwrapper('header.menu.logo')
      <div class="logo d-flex flex-column align-items-start">
        <a href="{{ shop_route('home.index') }}" class="d-inline-block">
          <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid" alt="{{ system_setting('base.meta_title', __('common.site_default_title')) }}">
        </a>
        <span class="mr-hoa-brand-tagline">{{ system_setting('base.shop_tagline', __('shop/mrhoa.tagline')) }}</span>
      </div>
      @endhookwrapper
      <div class="menu-wrap">
        @include('shared.menu-pc')
      </div>
      <div class="right-btn">
        <ul class="navbar-nav flex-row">
          @hookwrapper('header.menu.icon')
          <li class="nav-item"><a href="#offcanvas-search-top" data-bs-toggle="offcanvas" aria-controls="offcanvasExample" class="nav-link"><img src="{{ asset('image/icons/search.svg') }}" class="img-fluid"></a></li>
          @endhookwrapper
          <li class="nav-item">
            <a
              class="nav-link position-relative btn-right-cart {{ equal_route('shop.carts.index') ? 'page-cart' : '' }}"
              href="javascript:void(0);" role="button">
              <img src="{{ asset('image/icons/cart.svg') }}" class="img-fluid">
              <span class="cart-badge-quantity"></span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="header-mobile d-lg-none">
    <div class="mobile-content">
      <div class="left">
        <div class="mobile-open-menu"><img src="{{ asset('image/icons/menu.svg') }}" alt="{{ __('common.aria_menu') }}" class="img-fluid"></div>
        <div class="mobile-open-search" href="#offcanvas-search-top" data-bs-toggle="offcanvas"
             aria-controls="offcanvasExample">
             <img src="{{ asset('image/icons/search.svg') }}" class="img-fluid" alt="{{ __('common.aria_search') }}">
        </div>
      </div>
      <div class="center text-center"><a href="{{ shop_route('home.index') }}" class="d-inline-block">
          <img src="{{ image_origin(system_setting('base.logo')) }}" class="img-fluid" alt="{{ system_setting('base.meta_title', __('common.site_default_title')) }}"></a>
        <div class="mr-hoa-brand-tagline small">{{ system_setting('base.shop_tagline', __('shop/mrhoa.tagline')) }}</div>
      </div>
      <div class="right">
        <a href="{{ shop_route('carts.index') }}" class="nav-link m-cart position-relative"><img src="{{ asset('image/icons/cart.svg') }}" alt="{{ __('common.aria_cart') }}" class="img-fluid">
          <span class="cart-badge-quantity"></span></a>
      </div>
    </div>
  </div>
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-mobile-menu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">{{ __('common.menu') }}</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="{{ __('common.close') }}"></button>
    </div>
    <div class="offcanvas-body mobile-menu-wrap">
      @include('shared.menu-mobile')
    </div>
  </div>

  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas-right-cart"
       aria-labelledby="offcanvasRightLabel"></div>

  <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvas-search-top" aria-labelledby="offcanvasTopLabel">
    <div class="offcanvas-header">
      <input type="text" class="form-control input-group-lg border-0 fs-4" focus placeholder="{{ __('common.input') }}"
             value="{{ request('keyword') }}" data-lang="{{ locale() === system_setting('base.locale') ? '' : session()->get('locale') }}">
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="{{ __('common.close') }}"></button>
    </div>
  </div>
  @hook('header.after')
</header>
