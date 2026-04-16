<!DOCTYPE html>
<html dir="{{ current_language() }}" lang="{{ current_language() }}">
<head>
  <meta charset="UTF-8" />
  <title>Phiếu lấy hàng</title>
  <link href="{{ mix('/build/beike/admin/css/bootstrap.css') }}" rel="stylesheet">
</head>
<body>
<div class="container">
  <div id="print-button">
    <style media="print">.printer {display:none;} .btn {display:none;}</style>
    <p style="text-align: right;"><button class="btn btn-primary right" type="button" onclick="window.print()" class="printer">In</button></p>
  </div>
  @foreach ($orders as $order)
  <div style="page-break-after: always;">
    <h1 style="text-align: center;">{{ $order['store_name'] }} Phiếu lấy hàng</h1>
    <table class="table">
      <tbody>
      <tr>
        <td>
          <b>Tên khách hàng vận chuyển: </b> {{ $order['shipping_customer_name'] }}<br />
          <b>Điện thoại: </b> {{ $order['shipping_telephone'] }}<br/>
          <b>Email: </b> {{ $order['email'] }}<br/>
          <b>Địa chỉ giao hàng: </b> {{ $order['shipping_customer_name'] . "(" . $order['shipping_telephone'] . ")" . ' ' . $order['shipping_address_1'] . ' ' . $order['shipping_address_2'] . ' ' . $order['shipping_city'] . ' ' . $order['shipping_zone'] . ' ' . $order['shipping_country'] }}<br />
        </td>
        <td style="width: 50%;">
          <b>Mã đơn hàng: </b> {{ $order['number'] }}<br />
          <b>Ngày tạo: </b> {{ $order['created_at'] }}<br />
        </td>
      </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <thead>
      <tr>
        <td><b>STT</b></td>
        <td><b>Ảnh</b></td>
        <td><b>Sản phẩm</b></td>
        <td><b>SKU</b></td>
        <td class="text-right"><b>Số lượng</b></td>
        <td class="text-right"><b>Đơn giá</b></td>
        <td class="text-right"><b>Tổng</b></td>
      </tr>
      </thead>
      <tbody>
      @if ($order['order_products'])
      @foreach ($order['order_products'] as $product)
      <tr>
        <td>{{ $loop->iteration }}</td>
        <td><img class="img-thumbnail" src="{{ $product['image'] }}" alt=""></td>
        <td>{{ $product['name'] }}</td>
        <td>{{ $product['sku'] }}</td>
        <td class="text-right">{{ $product['quantity'] }}</td>
        <td class="text-right">{{ $product['price'] }}</td>
        <td class="text-right">{{ $product['total_format'] }}</td>
      </tr>
      @endforeach
      @endif
      </tbody>
    </table>
    <table class="table table-tdborder-no">
      <thead style="border-top: 1px solid #ddd;">
      <tr>
        <td><b>{{ __("admin/order.product_total") }}</b>: {{ $order['product_total'] }}</td>
        <td></td>
        <td><b>{{ __("admin/order.order_total") }}</b>: {{ $order['total'] }}</td>
      </tr>
      </thead>
      <tbody>
      <tr>
        <td colspan="3">
          <b>{{ $order['store_name'] }}</b> <br />
          <b>Điện thoại: </b> {{ $order['shipping_telephone'] }}<br />
          <b>Email: </b> {{ $order['email'] }}<br />
          <b>Website: </b> <a href="{{ $order['website'] }}">{{ $order['website'] }}</a></td>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
  @endforeach
</div>
</body>
</html>
