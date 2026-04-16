@hook('admin.dashboard.guide.before')
<div class="card mb-4 dashboard-guide-section">
  <div class="card-header d-flex justify-content-between align-items-start">
    <h5 class="card-title">{{ __('admin/guide.heading_title') }}</h5>
    <div class="cursor-pointer guide-close"><i class="bi bi-x-lg"></i></div>
  </div>
  <div class="card-body">
    <div class="tab-content">
      @hook('admin.dashboard.guide.before.tab-content')
      <div class="tab-pane fade show active" id="basic-tab">
        <div class="guide-info">
          <div class="left"><span><i class="bi bi-gear"></i></span></div>
          <div class="right">
            <p>{{ __('admin/guide.text_greeting') }}</p>
            <p>{{ __('admin/guide.text_greeting_1') }}</p>
            <p>{{ __('admin/guide.text_basic_1') }}</p>
            <ol class="mb-3">
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-general']) }}">
                  {{ __('admin/guide.button_setting_general') }}
                </a>
              </li>
              <li>
                <a href="{{ admin_route('settings.index', ['tab' => 'tab-image']) }}">
                  {{ __('admin/guide.button_setting_logo') }}
                </a>
              </li>
            </ol>
          </div>
        </div>
      </div>
      @hook('admin.dashboard.guide.after.tab-content')
    </div>
    <div class="tab-footer">
      <label class=""><input type="checkbox" name="hide_guide"> {{ __('admin/guide.button_hide') }}</label>
    </div>
  </div>
</div>
@hook('admin.dashboard.guide.after')

@push('footer')
  <script>
    $('.guide-close').on('click', function() {
      if ($('input[name="hide_guide"]').is(':checked')) {
        $http.put('settings/values', {guide: 0}, {hload: true});
      }

      $('.dashboard-guide-section').remove();
    });

    $('input[name="hide_guide"]').on('change', function() {
      setTimeout(() => {
        $('.guide-close').trigger('click');
      }, 200);
    })
  </script>
@endpush