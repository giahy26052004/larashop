<div class="manual-transfer-payment border rounded p-3 p-lg-4 bg-light">
  <h5 class="mb-3">Thanh toán QR chuyển khoản</h5>
  <p class="mb-3">
    Vui lòng quét QR để chuyển khoản đúng số tiền đơn hàng. Sau khi chuyển khoản, đơn hàng sẽ ở trạng thái chờ thanh toán và quản trị viên sẽ xác nhận thủ công.
  </p>

  @php
    $qrImage = system_setting('base.bank_qr_image', '');
    $bankInfo = system_setting('base.bank_transfer_info', '');
  @endphp

  @if ($qrImage)
    <div class="mb-3">
      <img src="{{ image_origin($qrImage) }}" alt="{{ __('shop/checkout.bank_qr_image_alt') }}" class="img-fluid border rounded" style="max-width: 280px;">
    </div>
  @endif

  <div class="mb-2">
    <strong>Số tiền:</strong> {{ $order['total_format'] }}
  </div>
  <div class="mb-2">
    <strong>Nội dung chuyển khoản:</strong> {{ $order['number'] }}
  </div>

  @if ($bankInfo)
    <div class="mt-3">
      <strong>Thông tin tài khoản:</strong>
      <div class="mt-2">{!! nl2br(e($bankInfo)) !!}</div>
    </div>
  @endif
</div>
