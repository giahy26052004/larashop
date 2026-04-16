<section class="module-item {{ $design ? 'module-item-design' : ''}}" id="module-{{ $module_id }}">
  @include('design._partial._module_tool')
  @php
    $promoTitle = system_setting('base.home_promo_title', $content['title'] ?? '');
    $promoDescription = system_setting('base.home_promo_description', $content['description'] ?? '');
    $promoImage = system_setting('base.home_promo_image', '');
    $bannerImage = $promoImage ? image_origin($promoImage) : ($content['image'] ?? '');
    $bannerBtnHref = $content['link'] ?? '#';
    $pathOnly = parse_url((string) $bannerBtnHref, PHP_URL_PATH);
    if (\Illuminate\Support\Facades\Route::has('shop.latest_products') && $pathOnly && str_contains($pathOnly, 'categories/100007')) {
      $bannerBtnHref = shop_route('latest_products');
    }
  @endphp

  <div class="module-info mb-3 mb-md-5">
    <div class="img-text-banner-wrap {{ $content['module_size'] ?? 'container-fluid' }}">
      <div class="row">
        <div class="col-12 col-lg-6 pe-lg-0">
          <div class="text-wrap" style="background-color: {{ $content['bg_color'] }}; color: {{ $content['text_color'] ?? '#222' }}">
            <div class="fs-2 fw-bold title">{{ $promoTitle }}</div>
            <p class="description">{{ $promoDescription }}</p>
            <a href="{{ $bannerBtnHref }}" class="btn" style="background-color: {{ $content['btn_bg'] ?? '#fd560f' }}; color: {{ $content['btn_color'] ?? '#fff' }}">{{ __('common.view_more') }}</a>
          </div>
        </div>
        <div class="col-12 col-lg-6 ps-lg-0">
          <div class="img-wrap">
            <img src="{{ $bannerImage }}" class="img-fluid seo-img" alt="{{ $content['image_alt'] ?? '' }}">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>