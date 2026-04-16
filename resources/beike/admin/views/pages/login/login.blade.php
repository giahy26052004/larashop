<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <link rel="icon" href="{{ image_origin(system_setting('base.favicon')) }}?v={{ config('beike.build') }}" type="image/png">
  <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}?v={{ config('beike.build') }}">
  <link rel="apple-touch-icon" href="{{ image_origin(system_setting('base.favicon')) }}?v={{ config('beike.build') }}">
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
  <link href="{{ mix('build/beike/admin/css/app.css') }}" rel="stylesheet">
  <title>{{ system_setting('base.meta_title', config('app.name', 'BeikeShop')) }} - Đăng nhập trang quản trị</title>
</head>
<body class="page-login">
  <div class="d-flex align-items-center vh-100 pt-2 pt-sm-5 pb-4 pb-sm-5">
    <div class="container">
      <div class="card">
        <div class="w-480">
          <div class="card-header mt-3 mb-4">
            <h4 class="fw-bold">Đăng nhập trang quản trị</h4>
            {{-- <div class="text-muted fw-normal">Đăng nhập trang quản trị</div> --}}
          </div>

          <div class="card-body">
            <form action="{{ admin_route('login.store') }}" method="post">
              @csrf

              <div class="form-floating mb-4">
                <input type="text" name="email" class="form-control" id="email-input" value="{{ old('email', $admin_email ?? '') }}" placeholder="Email">
                <label for="email-input">Email</label>
                @error('email')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              <div class="form-floating mb-5">
                <input type="password" name="password" class="form-control" id="password-input" value="{{ old('password', $admin_password ?? '') }}" placeholder="Mật khẩu">
                <label for="password-input">Mật khẩu</label>
                @error('password')
                  <x-admin::form.error :message="$message" />
                @enderror
              </div>

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              <div class="d-grid mb-4"><button type="submit" class="btn btn-lg btn-primary">Đăng nhập</button></div>
              <a href="{{ admin_route('forgotten.index') }}" class="text-muted"><i class="bi bi-question-circle"></i> Quên mật khẩu</a>
            </form>
          </div>
        </div>
      </div>

      <p class="text-center text-secondary mt-5">
        <a href="https://beikeshop.com/" class="ms-2" target="_blank">{{ config('app.name') }}</a>
        v{{ config('beike.version') }}({{ config('beike.build') }})
        &copy; {{ date('Y') }} Bản quyền đã đăng ký</p>
    </div>
  </div>
</body>
</html>
