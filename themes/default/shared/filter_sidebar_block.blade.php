<div class="mb-4 module-category-wrap">
  <h4 class="mb-3"><span>{{ __('product.category') }}</span></h4>
  <ul class="sidebar-widget mb-0" id="category-one">
    @foreach ($all_categories as $key_a => $category_all)
    @if (!$category_all['active']) @continue @endif
    @if ($category_all['parent_id'] ?? 0) @continue @endif
    <li class="{{ $category_all['id'] == $category->id ? 'active' : ''}}">
      <a href="{{ $category_all['url'] }}" title="{{ $category_all['name'] }}" class="category-href">{{ $category_all['name'] }}</a>
    </li>
    @endforeach
  </ul>
</div>

<div class="filter-box mr-hoa-filter-box">
  @if ($filter_data['price']['min'] != $filter_data['price']['max'])
    @hookwrapper('category.filter.sidebar.price')
    @push('header')
      <script src="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.js') }}"></script>
      <link rel="stylesheet" href="{{ asset('vendor/jquery/jquery-ui/jquery-ui.min.css') }}">
    @endpush

    @if (system_setting('base.multi_filter.price_filter', 1))
      <div class="card mr-hoa-filter-card">
        <div class="card-header border-0 p-0">
          <h4 class="mr-hoa-filter-title mb-2">{{ __('product.price') }}</h4>
        </div>
        <div class="card-body p-0 pt-1">
          <div id="price-slider" class="mb-2"><div class="slider-bg"></div></div>
          <div class="text-secondary price-range d-flex justify-content-between">
            <div class="d-flex align-items-center wp-100">
              {{ __('common.text_form') }}
              <span class="min ms-1 input-group-sm"><input type="text" value="{{ $filter_data['price']['select_min'] }}" class="form-control price-select-min"></span>
            </div>
            <div class="d-flex align-items-center wp-100">
              {{ __('common.text_to') }}
              <span class="max ms-1 input-group-sm"><input type="text" value="{{ $filter_data['price']['select_max'] }}" class="form-control price-select-max"></span>
            </div>
          </div>
          <input value="{{ $filter_data['price']['min'] }}" class="price-min d-none">
          <input value="{{ $filter_data['price']['max'] }}" class="price-max d-none">
        </div>
      </div>
    @endif
    @endhookwrapper
  @endif

  @hookwrapper('category.filter.sidebar.attr')
  @foreach ($filter_data['attr'] as $index => $attr)
  <div class="card mr-hoa-filter-card">
    <div class="card-header border-0 p-0 fw-normal">
      <h4 class="mr-hoa-filter-title mb-2">{{ $attr['name'] }}</h4>
    </div>
    <ul class="list-group list-group-flush attribute-item mr-hoa-filter-attr-list" data-attribute-id="{{ $attr['id'] }}">
      @foreach ($attr['values'] as $value_index => $value)
      <li class="list-group-item border-0 px-0 py-1">
        <label class="form-check mr-hoa-filter-check d-flex align-items-start gap-2 mb-0">
          <input class="form-check-input attr-value-check flex-shrink-0 mt-1" data-attr="{{ $index }}" data-attrval="{{ $value_index }}" {{ $value['selected'] ? 'checked' : '' }} name="6" type="checkbox" value="{{ $value['id'] }}">
          <span class="mr-hoa-filter-check-label">{{ $value['name'] }}</span>
        </label>
      </li>
      @endforeach
    </ul>
  </div>
  @endforeach
  @endhookwrapper
</div>

@push('add-scripts')
<script>
  const currencyRate = {{ current_currency_rate() }};
  const isVnd = {{ system_setting('base.currency') === 'VND' ? 'true' : 'false' }};
  function formatMoneyInput(n) {
    const raw = String(n ?? '').replace(/\D/g, '');
    const v = Math.round(Number(raw || 0));
    if (!isVnd) return String(v);
    return v.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }
  $(document).ready(function() {
    if (!$('#price-slider').length) {
      return;
    }

    const priceStep = isVnd ? 1000 : 0.01;

    $("#price-slider").slider({
      range: true,
      step: priceStep,
      min: {{ $filter_data['price']['min'] ?? 0 }},
      max: {{ $filter_data['price']['max'] ?? 0 }},
      values: [{{ $filter_data['price']['select_min'] }}, {{ $filter_data['price']['select_max'] }}],
      change: function(event, ui) {
        filterProductData();
      },
      slide: function(event, ui) {
        const a = Math.round(ui.values[0] * currencyRate);
        const b = Math.round(ui.values[1] * currencyRate);
        $('.price-select-min').val(isVnd ? formatMoneyInput(a) : a.toFixed(2));
        $('.price-select-max').val(isVnd ? formatMoneyInput(b) : b.toFixed(2));
      }
    });

    $('.price-select-min, .price-select-max').change(function(event) {
      filterProductData()
    });

    $('.price-select-min, .price-select-max').on('input', function() {
      if (isVnd) {
        this.value = this.value.replace(/[^0-9]/g, '');
      } else {
        this.value = this.value.replace(/[^0-9.]/g, '');
      }
    });

    if (isVnd) {
      $('.price-select-min').val(function (_, v) { return formatMoneyInput(v); });
      $('.price-select-max').val(function (_, v) { return formatMoneyInput(v); });
    }
  })

  $('.child-category').each(function(index, el) {
    if ($(this).hasClass('active')) {
      $(this).parents('ul').addClass('show').siblings('button').removeClass('collapsed')
      $(this).parents('li').addClass('active')
    }
  });
</script>
@endpush
