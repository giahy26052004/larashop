@extends('admin::layouts.master')

@section('title', __('admin/setting.index'))

@section('page-bottom-btns')
  <button type="button" class="btn btn-lg w-min-100 btn-primary submit-form" form="app">{{ __('common.save') }}</button>
  <button class="btn btn-lg btn-default w-min-100 ms-3" onclick="bk.back()">{{ __('common.return') }}</button>
@endsection

@push('header')
  <script src="{{ asset('vendor/cropper/cropper.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('vendor/cropper/cropper.min.css') }}">
@endpush

@section('content')
  <div id="plugins-app-form" class="card h-min-600">
    <div class="card-body">
      @hook('admin.setting.content.before')
      <form action="{{ admin_route('settings.store') }}" class="needs-validation" novalidate method="POST" id="app" v-cloak>
        @csrf
        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">
            {!! session('error') !!}
          </div>
        @endif
        <ul class="nav nav-tabs nav-bordered mb-3  mb-lg-5" role="tablist">
          @hook('admin.setting.nav.before')
          <li class="nav-item" role="presentation">
            <a class="nav-link active" data-bs-toggle="tab" href="#tab-general">{{ __('admin/setting.basic_settings') }}</a>
          </li>
          <li class="nav-item" role="presentation">
            <a class="nav-link" data-bs-toggle="tab" href="#tab-image">{{ __('admin/guide.button_setting_logo') }}</a>
          </li>
          @hook('admin.setting.nav.after')
        </ul>

        <div class="tab-content">
          @hook('admin.setting.tab_content.before')
          <div class="tab-pane fade show active" id="tab-general">
            @hook('admin.setting.general.before')
            <x-admin-form-input name="meta_title" title="{{ __('admin/setting.meta_title') }}" value="{{ old('meta_title', system_setting('base.meta_title', '')) }}" required />
            <x-admin-form-textarea name="meta_description" title="{{ __('admin/setting.meta_description') }}" value="{{ old('meta_description', system_setting('base.meta_description', '')) }}" />
            <x-admin-form-textarea name="meta_keywords" title="{{ __('admin/setting.meta_keywords') }}" value="{{ old('meta_keywords', system_setting('base.meta_keywords', '')) }}" />
            <x-admin-form-input name="telephone" title="{{ __('admin/setting.telephone') }}" value="{{ old('telephone', system_setting('base.telephone', '')) }}" />
            <x-admin-form-input name="email" title="{{ __('admin/setting.email') }}" value="{{ old('email', system_setting('base.email', '')) }}" />
            <x-admin-form-input name="home_promo_title" title="Tiêu đề banner trang chủ" value="{{ old('home_promo_title', system_setting('base.home_promo_title', '')) }}" />
            <x-admin-form-textarea name="home_promo_description" title="Mô tả banner trang chủ" value="{{ old('home_promo_description', system_setting('base.home_promo_description', '')) }}" />
            <x-admin::form.row title="Ảnh banner trang chủ">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="16/9">
                <img src="{{ image_resize(old('home_promo_image', system_setting('base.home_promo_image', '')), 120, 80) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('home_promo_image', system_setting('base.home_promo_image', '')) }}" name="home_promo_image">
              <div class="help-text font-size-12 lh-base">Ảnh riêng cho khối giới thiệu trên trang chủ.</div>
            </x-admin::form.row>

            <hr class="my-4">

            <x-admin::form.row title="QR chuyển khoản">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="1/1">
                <img src="{{ image_resize(old('bank_qr_image', system_setting('base.bank_qr_image', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('bank_qr_image', system_setting('base.bank_qr_image', '')) }}" name="bank_qr_image">
              <div class="help-text font-size-12 lh-base">Tải ảnh QR để khách quét chuyển khoản.</div>
            </x-admin::form.row>

            <x-admin-form-textarea
              name="bank_transfer_info"
              title="Thông tin chuyển khoản"
              value="{{ old('bank_transfer_info', system_setting('base.bank_transfer_info', '')) }}" />

            <hr class="my-4">
            <h6 class="mb-3">Giỏ hàng (shop) — phí giao dự kiến</h6>
            <x-admin-form-input
              name="cart_shipping_estimate"
              title="Phí giao hàng ước tính (số, theo đơn vị tiền tệ hiện tại)"
              value="{{ old('cart_shipping_estimate', system_setting('base.cart_shipping_estimate', '30000')) }}"
              placeholder="30000"
            />
            <div class="help-text font-size-12 lh-base mb-3">Hiển thị ở cột tổng <strong>giỏ hàng</strong> (ước tính). Trang <strong>thanh toán</strong> chỉ dùng một bảng tổng từ hệ thống; không lặp lại các dòng tiền ở đây.</div>
            <x-admin-form-input
              name="cart_shipping_line_title"
              title="Tiêu đề dòng phí giao"
              value="{{ old('cart_shipping_line_title', system_setting('base.cart_shipping_line_title', 'Phí giao hàng (30.000₫)')) }}"
            />
            <x-admin-form-textarea
              name="cart_shipping_notice"
              title="Mô tả phí giao (hiển thị dưới tiêu đề)"
              value="{{ old('cart_shipping_notice', system_setting('base.cart_shipping_notice', 'Phí cố định 30.000₫. Lara Flowers sẽ xác nhận lại phí giao hàng theo khu vực (nếu có) và thông báo cho khách hàng.')) }}"
            />
            <x-admin-form-textarea
              name="cart_shipping_checkout_note"
              title="Ghi chú nhỏ (dưới phí giao)"
              value="{{ old('cart_shipping_checkout_note', system_setting('base.cart_shipping_checkout_note', '')) }}"
            />
            <x-admin-form-textarea
              name="checkout_transfer_instructions"
              title="Hướng dẫn thanh toán (khối dưới tổng đơn — checkout)"
              value="{{ old('checkout_transfer_instructions', system_setting('base.checkout_transfer_instructions', '')) }}"
            />
            <div class="help-text font-size-12 lh-base mb-3">Hiển thị dạng ô tóm tắt (cùng kiểu phí giao dự kiến). Để trống thì dùng nội dung mặc định trong ngôn ngữ shop.</div>
            @hook('admin.setting.general.after')
          </div>

          <div class="tab-pane fade" id="tab-image">

            @hook('admin.setting.image.before')

            <x-admin::form.row title="{{ __('admin/setting.shop_logo') }}">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
                <img src="{{ image_resize(old('logo', system_setting('base.logo', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('logo', system_setting('base.logo', '')) }}" name="logo">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 380*100</div>
            </x-admin::form.row>

            <x-admin::form.row title="{{ __('admin/setting.admin_logo') }}">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="380/100">
                <img src="{{ image_resize(old('admin_logo', system_setting('base.admin_logo', 'image/logo.png'))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('admin_logo', system_setting('base.admin_logo', 'image/logo.png')) }}" name="admin_logo">
              <div class="help-text font-size-12 lh-base">{{ __('common.recommend_size') }} 388*73</div>
            </x-admin::form.row>

            <x-admin::form.row title="{{ __('admin/setting.favicon') }}">
              <div class="open-crop cursor-pointer bg-light wh-80 border d-flex justify-content-center align-items-center me-2 mb-2 position-relative" ratio="1/1">
                <img src="{{ image_resize(old('favicon', system_setting('base.favicon', ''))) }}" class="img-fluid">
              </div>
              <input type="hidden" value="{{ old('favicon', system_setting('base.favicon', '')) }}" name="favicon">
              <div class="help-text font-size-12 lh-base">{{ __('admin/setting.favicon_info') }}</div>
            </x-admin::form.row>

            @hook('admin.setting.image.after')
          </div>

          @hook('admin.setting.after')
        </div>

        <x-admin::form.row title="">
          <button type="submit" class="btn btn-primary d-none mt-4">{{ __('common.submit') }}</button>
        </x-admin::form.row>
      </form>
    </div>
  </div>

  <div class="modal fade" id="modal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex align-items-center">
            <h5 class="modal-title" id="exampleModalLabel">{{ __('shop/account/edit.crop') }}</h5>
            <div class="cropper-size ms-4">{{ __('common.cropper_size') }}：<span></span></div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="img-container">
            <img id="cropper-image" src="{{ image_resize('/') }}" class="img-fluid">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('shop/common.cancel') }}</button>
          <button type="button" class="btn btn-primary cropper-crop">{{ __('shop/common.confirm') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('footer')
  <script>
    @if (session('success'))
      layer.msg('{{ session('success') }}')
    @endif

    const country_id = {{ system_setting('base.country_id', '1') }};
    const zone_id = {{ system_setting('base.zone_id', '1') ?: 1 }};

    // 获取省份
    const getZones = (country_id) => {
      $http.get(`countries/${country_id}/zones`, null, {hload: true}).then((res) => {
        if (res.data.zones.length > 0) {
          $('select[name="zone_id"]').html('');
          res.data.zones.forEach((zone) => {
            $('select[name="zone_id"]').append(`
              <option ${zone_id == zone.id ? 'selected' : ''} value="${zone.id}">${zone.name}</option>
            `);
          });
        } else {
          $('select[name="zone_id"]').html(`
            <option value="">{{ __('common.please_choose') }}</option>
          `);
        }
      })
    }

    $(function() {
      const line = bk.getQueryString('line');
      getZones(country_id);

      $('select[name="country_id"]').on('change', function () {
        getZones($(this).val());
      });

      if (line) {
        $(`textarea[name="${line}"], select[name="${line}"], input[name="${line}"]`).parents('.row').addClass('active-line');

        setTimeout(() => {
          $('div').removeClass('active-line');
        }, 1200);
      }

      let smtpHost = $('input[name="smtp[host]"]').val();
      if (smtpHost && smtpHost.includes('smtp.qq.com')) {
        $('.smtp-qq-hint').removeClass('d-none');
      }

      $(document).on('input', 'input[name="smtp[host]"]', function () {
        if ($(this).val().includes('smtp.qq.com')) {
          $('.smtp-qq-hint').removeClass('d-none');
        } else {
          $('.smtp-qq-hint').addClass('d-none');
        }
      });

      $('.nav-tabs a').on ('click', function (e) {
        const formAction = @json(admin_route('settings.store'));
        const tab = $(this).attr('href').replace('#', '');
        $('form#app').attr('action', formAction + '?tab=' + tab);
      });
    });

  </script>

  <script>
    let ratio = 1;
    let $crop = null
    var cropper;

    $(function() {
      $('.open-crop').click(function() {
        var image = document.getElementById('cropper-image');
        $crop = $(this);
        ratio = $(this).attr('ratio')
        var cropper;
        var $input = $('<input type="file" accept="image/*" class="d-none">');
        $input.click();
        $input.change(function() {
          var files = this.files;
          var done = function(url) {
            image.src = url;
            $('#modal').modal('show');
          };

          if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
              done(reader.result);
            };
            reader.readAsDataURL(files[0]);
          }
        });
      });

      $('input[name="show_price_after_login"]').change(function () {
        if ($(this).val() == 1 && $('input[name="guest_checkout"]').prop('checked') == true) {
          $('input[name="guest_checkout"]').prop('checked', true);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });

      $('input[name="guest_checkout"]').change(function () {
        if ($(this).val() == 1 && $('input[name="show_price_after_login"]').prop('checked') == true) {
          $('input[name="show_price_after_login"]').prop('checked', 1);
          $('.show-price-error-text').addClass('text-danger fw-bold');
          setTimeout(() => {
            $('.show-price-error-text').removeClass('text-danger fw-bold');
          }, 1200);
        }
      });
    });

    $('#modal').on('shown.bs.modal', function() {
      var image = document.getElementById('cropper-image');
      cropper = new Cropper(image, {
        initialAspectRatio: ratio.split('/')[0] / ratio.split('/')[1],
        autoCropArea: 1,
        viewMode: 1,
        // 回调 获取尺寸
        crop: function(event) {
          $('.cropper-size span').html(parseInt(event.detail.width) + ' * ' + parseInt(event.detail.height))
        }
      });
    }).on('hidden.bs.modal', function() {
      cropper.destroy();
      cropper = null;
    });

    $('.cropper-crop').click(function(event) {
      var canvas;

      $('#modal').modal('hide');

      if (cropper) {
        canvas = cropper.getCroppedCanvas({
          imageSmoothingQuality: 'high',
          width: 800, //最大宽度
          height: 800, //最大高度
        });
        canvas.toBlob(function(blob) {
          var formData = new FormData();

          formData.append('file', blob, 'avatar.png');
          formData.append('type', 'avatar');
          $http.post('{{ shop_route('file.store') }}', formData).then(res => {
            $crop.find('img').attr('src', res.data.url);
            $crop.next('input').val(res.data.value);
          })
        });
      }
    });
  </script>

  <script>
    let app = new Vue({
      el: '#app',
      data: {
        mail_engine: @json(old('mail_engine', system_setting('base.mail_engine', ''))),
        express_company: @json(old('express_company', system_setting('base.express_company', []))),

        source: {
          mailEngines: [
            {name: '{{ __('admin/builder.text_no') }}', code: ''},
            {name: 'SMTP', code: 'smtp'},
            {name: 'Sendmail', code: 'sendmail'},
            {name: 'Mailgun', code: 'mailgun'},
            {name: 'Log', code: 'log'},
          ]
        },
      },
      methods: {
        addCompany() {
          if (typeof this.express_company == 'string') {
            this.express_company = [];
          }

          this.express_company.push({name: '', code: ''})
        },
      }
    });
  </script>
@endpush



