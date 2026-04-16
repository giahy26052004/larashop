@extends('admin::layouts.master')

@section('title', 'Bảng điều khiển')

@section('body-class', 'admin-home')

@section('content')
  @hookwrapper('admin.home.dashboard.totals')
  @include('admin::pages.dashboard.totals')
  @endhookwrapper

  @hookwrapper('admin.home.dashboard.orders_chart')
  @include('admin::pages.dashboard.orders_chart')
  @endhookwrapper

  @hookwrapper('admin.home.index.content.footer')
  @hook('admin.home.index.content.footer')
  @endhookwrapper
@endsection
