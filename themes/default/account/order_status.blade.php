@hookwrapper('account.order.status.tab')
<ul class="nav nav-tabs order-status-wrap">
  <li class="nav-item" role="presentation">
    <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ shop_route('account.order.index') }}">{{ __('order.order_all') }}</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link {{ request('status') == 'unpaid' ? 'active' : '' }}" href="{{ shop_route('account.order.index', ['status' => 'unpaid']) }}">{{ __('order.unpaid') }}</a>
  </li>
  <li class="nav-item" role="presentation">
    <a class="nav-link {{ request('status') == 'paid' ? 'active' : '' }}" href="{{ shop_route('account.order.index', ['status' => 'paid']) }}">{{ __('order.paid') }}</a>
  </li>
</ul>
@endhookwrapper