@extends('admin::layouts.master')

@section('title', 'Đơn hàng')

@section('page-bottom-btns')
@hook('order.detail.title.right')
@endsection

@section('page-title-right')
  <a href="{{ admin_route('orders.shipping.get') }}?order_id={{ $order->id }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer-fill"></i> In đơn hàng</a>
@endsection

@section('content')
  @php
    $statusClassMap = [
      'unpaid' => 'bg-warning text-dark',
      'paid' => 'bg-success',
      'shipped' => 'bg-info text-dark',
      'completed' => 'bg-primary',
      'cancelled' => 'bg-danger',
    ];
    $orderComment = (string) ($order->comment ?? '');
    $deliveryTime = null;
    if (preg_match('/^Giờ giao mong muốn:\s*(.+)$/mu', $orderComment, $m)) {
      $deliveryTime = trim($m[1]);
      $orderComment = trim(preg_replace('/^Giờ giao mong muốn:\s*.+$/mu', '', $orderComment));
    }
  @endphp

  @hook('admin.order.form.content.before')

  @hookwrapper('admin.order.form.base')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Thông tin đơn hàng</h6></div>
    <div class="card-body order-top-info">
      <div class="row">
        <div class="col-lg-4 col-12">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>Mã đơn hàng：</td>
                <td>{{ $order->number }}</td>
              </tr>
              <tr>
                <td>Phương thức thanh toán：</td>
                <td>{{ $order->payment_method_name }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="col-lg-4 col-12">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>Tổng：</td>
                <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
              </tr>
              <tr>
                <td>Tên khách：</td>
                <td>{{ $order->customer_name }}</td>
              </tr>
              @if ($order->email)
                <tr>
                  <td>Email：</td>
                  <td>{{ $order->email }}</td>
                </tr>
              @endif
              @if ($deliveryTime)
                <tr>
                  <td>Giờ nhận hoa：</td>
                  <td>{{ mrhoa_format_delivery_wish($deliveryTime) }}</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
        <div class="col-lg-4 col-12">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <td>Ngày tạo：</td>
                <td>{{ $order->created_at }}</td>
              </tr>
              <tr>
                <td>Ngày cập nhật：</td>
                <td>{{ $order->updated_at }}</td>
              </tr>
              @hook('admin.order.form.base.updated_at.after')
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endhookwrapper

  @hookwrapper('admin.order.form.address')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Thông tin địa chỉ</h6></div>
    <div class="card-body">
      <table class="table table-no-mb">
        <thead class="">
          <tr>
            @if ($order->shipping_country)
            <th>Địa chỉ giao hàng</th>
            @endif
            <th>Địa chỉ thanh toán</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            @if ($order->shipping_country)
            <td>
              <div>
                Tên：{{ $order->shipping_customer_name }}
                @if ($order->shipping_telephone)
                ({{ $order->shipping_telephone }})
                @endif
              </div>
              <div>
                Địa chỉ：
                {{ $order->shipping_address_1 }}
                {{ $order->shipping_address_2 }}
                {{ $order->shipping_city }}
                {{ $order->shipping_zone }}
                {{ $order->shipping_country }}
              </div>
              @if ($order->shipping_zipcode)
                <div>Mã bưu chính：{{ $order->shipping_zipcode }}</div>
              @endif
            </td>
            @endif
            <td>
              <div>
                Tên：{{ $order->payment_customer_name }}
                @if ($order->payment_telephone)
                ({{ $order->payment_telephone }})
                @endif
              </div>
              <div>
                Địa chỉ：
                {{ $order->payment_address_1 }}
                {{ $order->payment_address_2 }}
                {{ $order->payment_city }}
                {{ $order->payment_zone }}
                {{ $order->payment_country }}
              </div>
              @if ($order->payment_zipcode)
                <div>Mã bưu chính：{{ $order->payment_zipcode }}</div>
              @endif
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  @endhookwrapper

  @foreach ($html_items as $item)
    {!! $item !!}
  @endforeach

  @can('orders_update_status')
  @hookwrapper('admin.order.form.status')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Trạng thái đơn hàng</h6></div>
    <div class="card-body" id="app">
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item label="Trạng thái hiện tại">
          <span class="badge {{ $statusClassMap[$order->status] ?? 'bg-secondary' }}">
            {{ $order->status_format }}
          </span>
        </el-form-item>
        @if (count($statuses))
          <el-form-item label="Chuyển sang trạng thái" prop="status">
            <el-select class="wp-200" size="small" v-model="form.status" placeholder="Vui lòng chọn">
              <el-option
                v-for="item in statuses"
                :key="item.status"
                :label="item.name"
                :value="item.status">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Đơn vị vận chuyển" v-if="form.status == 'shipped'" prop="express_code">
            <el-select class="wp-200" size="small" v-model="form.express_code" placeholder="Vui lòng chọn">
              <el-option
                v-for="item in source.express_company"
                :key="item.code"
                :label="item.name"
                :value="item.code">
              </el-option>
            </el-select>
            <a href="{{ admin_route('settings.index') }}?tab=tab-express-company" target="_blank" class="ms-2">Đến cài đặt</a>
          </el-form-item>
          <el-form-item label="Mã vận đơn" v-if="form.status == 'shipped'" prop="express_number">
            <el-input class="w-max-500" v-model="form.express_number" size="small" v-if="form.status == 'shipped'" placeholder="Mã vận đơn"></el-input>
          </el-form-item>
          {{-- <el-form-item label="{{ __('admin/order.notify') }}">
            <el-checkbox :true-label="1" :false-label="0" v-model="form.notify"></el-checkbox>
          </el-form-item> --}}
          <el-form-item label="Ghi chú">
            <textarea class="form-control w-max-500" v-model="form.comment"></textarea>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitForm('form')">Cập nhật trạng thái</el-button>
          </el-form-item>
        @endif
      </el-form>
    </div>
  </div>
  @endhookwrapper
  @endcan

  @hookwrapper('admin.order.form.products')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Thông tin sản phẩm</h6></div>
    <div class="card-body">
      <div class="table-push">
        <table class="table ">
          <thead class="">
            <tr>
              <th>ID</th>
              <th>Tên sản phẩm</th>
              <th class="">SKU</th>
              <th>Giá</th>
              <th class="">Số lượng</th>
              <th class="text-end">Thành tiền</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderProducts as $product)
            <tr>
              <td>{{ $product->product_id }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="border d-flex justify-content-center align-items-center wh-60 me-2"><img src="{{ image_resize($product->image) }}" class="img-fluid max-h-100"></div>{{ $product->name }}
                  @hook('admin.order_form.product_name.after', $product)
                </div>
              </td>
              <td class="">{{ $product->product_sku }}</td>
              <td>{{ currency_format($product->price, $order->currency_code, $order->currency_value) }}</td>
              <td class="">{{ $product->quantity }}</td>
              <td class="text-end">{{ currency_format($product->price * $product->quantity, $order->currency_code, $order->currency_value) }}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            @foreach ($order->orderTotals as $orderTotal)
              <tr>
                <td colspan="5" class="text-end">{{ $orderTotal->title }}</td>
                <td class="text-end"><span class="fw-bold">{{ currency_format($orderTotal->value, $order->currency_code, $order->currency_value) }}</span></td>
              </tr>
            @endforeach
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  @endhookwrapper

  @if ($orderComment)
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">Ghi chú đơn hàng</h6></div>
      <div class="card-body">{{ $orderComment }}</div>
    </div>
  @endif

  @if (false && $order->orderPayments)
    @hookwrapper('admin.order.form.payments')
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">Lịch sử thanh toán</h6></div>
      <div class="card-body">
        <div class="table-push">
          <table class="table">
            <thead class="">
              <tr>
                <th>Mã đơn</th>
                <th>Mã giao dịch</th>
                <th>Yêu cầu</th>
                <th>Phản hồi</th>
                <th>Callback</th>
                <th>Biên lai</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderPayments as $payment)
              <tr>
                <td>{{ $payment->order_id }}</td>
                <td>{{ $payment->transaction_id }}</td>
                <td>{{ $payment->request }}</td>
                <td>{{ $payment->response }}</td>
                <td>{{ $payment->callback }}</td>
                <td>
                  @if ($payment->receipt)
                  <a href="{{ image_origin($payment->receipt) }}" target="_blank">Xem biên lai</a>
                  @endif
                </td>
                <td>{{ $payment->created_at }}</td>
                <td>{{ $payment->updated_at }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    @endhookwrapper
  @endif

  @if (false && $order->orderShipments->count())
    @hookwrapper('admin.order.form.shipments')
    <div class="card mb-4">
      <div class="card-header"><h6 class="card-title">Vận đơn</h6></div>
      <div class="card-body">
        <div class="table-push">
          <table class="table">
            <thead class="">
              <tr>
                <th>Đơn vị vận chuyển</th>
                <th>Mã vận đơn</th>
                <th>Ngày cập nhật</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($order->orderShipments as $ship)
              <tr data-id="{{ $ship->id }}">
                <td>
                  <div class="edit-show express-company">{{ $ship->express_company }}</div>
                  @if($expressCompanies)
                  <select class="form-select edit-form express-code d-none" aria-label="Default select example">
                    @foreach ($expressCompanies as $item)
                      <option value="{{ $item['code'] }}" {{ $ship->express_code == $item['code'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                    @endforeach
                  </select>
                  @endif
                </td>
                <td>
                  <div class="edit-show">{{ $ship->express_number }}</div>
                  <input type="text" class="form-control edit-form express-number d-none" placeholder="Mã vận đơn" value="{{ $ship->express_number }}">
                </td>
                <td>
                  <div class="d-flex justify-content-between align-items-center">
                    {{ $ship->created_at }}
                    <div class="btn btn-outline-primary btn-sm edit-shipment">Sửa</div>
                    <div class="d-none shipment-tool">
                      <div class="btn btn-primary btn-sm">Xác nhận</div>
                      <div class="btn btn-outline-secondary btn-sm">Hủy</div>
                    </div>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="text-end">
                  <a href="#" class="btn btn-sm btn-outline-secondary add-express">Thêm vận đơn</a>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
    @endhookwrapper
  @endif

  @hookwrapper('admin.order.form.history')
  <div class="card mb-4">
    <div class="card-header"><h6 class="card-title">Lịch sử hoạt động</h6></div>
    <div class="card-body">
      <div class="table-push">
        <table class="table ">
          <thead class="">
            <tr>
              <th>Trạng thái</th>
              <th>Bình luận</th>
              <th>Ngày cập nhật</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($order->orderHistories as $orderHistory)
              <tr>
                <td>
                  <span class="badge {{ $statusClassMap[$orderHistory->status] ?? 'bg-secondary' }}">
                    {{ $orderHistory->status_format }}
                  </span>
                </td>
                <td>{{ $orderHistory->comment }}</td>
                <td>{{ $orderHistory->created_at }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endhookwrapper

  @hook('admin.order.form.content.after')
@endsection

@push('footer')
  <script>
    const express_company = @json(system_setting('base.express_company', []));

    $('.add-express').on('click', function(e) {
      e.preventDefault();

      if (!express_company) {
        layer.alert('Vui lòng cấu hình đơn vị vận chuyển trước khi thêm vận đơn.', {
          title: 'Lưu ý',
          btn: ['Cài đặt đơn vị vận chuyển'],
          btn1: function(index, layero) {
            window.open('{{ admin_route('settings.index') }}?tab=tab-express-company');
          }
        });
        return;
      }

      let html = '<div class="px-3 pt-3 add-express-wrap">';
      html += '<div class="form-group mb-2">';
      html += '<label for="express_company" class="form-label">Đơn vị vận chuyển</label>';
      html += '<select class="form-select" id="express_company" aria-label="Default select example">';
      html += '<option value="">Vui lòng chọn</option>';
      express_company.forEach(item => {
        html += `<option value="${item.code}">${item.name}</option>`;
      });
      html += '</select>';
      html += '</div>';
      html += '<div class="form-group mb-2">';
      html += '<label for="express_number" class="form-label">Mã vận đơn</label>';
      html += '<input type="text" class="form-control" id="express_number" placeholder="Mã vận đơn">';
      html += '</div>';
      html += '</div>';

      layer.open({
        type: 1,
        title: 'Thêm vận đơn',
        content: html,
        area: ['400px', '300px'],
        btn: ['Hủy', 'Xác nhận'],
        btn2: function(index, layero) {
          const express_code = $('#express_company').val();
          const express_number = $('#express_number').val();
          if (!express_code) {
            layer.msg('Vui lòng chọn đơn vị vận chuyển.');
            return false;
          }
          if (!express_number) {
            layer.msg('Vui lòng nhập mã vận đơn.');
            return false;
          }

          $http.post(`/orders/{{ $order->id }}/shipments`, {express_code, express_number}).then((res) => {
            layer.msg(res.message);
            window.location.reload();
          });

          return false; // 阻止默认关闭行为
        }
      });
    });
  </script>

  @can('orders_update_status')
  <script>
    $('.edit-shipment').click(function() {
      $(this).siblings('.shipment-tool').removeClass('d-none');
      $(this).addClass('d-none');

      $(this).parents('tr').find('.edit-show').addClass('d-none');
      $(this).parents('tr').find('.edit-form').removeClass('d-none');
      @if(!$expressCompanies)
      $(this).parents('tr').find('.express-company').removeClass('d-none');
      @endif
    });

    $('.shipment-tool .btn-outline-secondary').click(function() {
      $(this).parent().siblings('.edit-shipment').removeClass('d-none');
      $(this).parent().addClass('d-none');

      $(this).parents('tr').find('.edit-show').removeClass('d-none');
      $(this).parents('tr').find('.edit-form').addClass('d-none');
    });

    $('.shipment-tool .btn-primary').click(function() {
      const id = $(this).parents('tr').data('id');
      const express_code = $(this).parents('tr').find('.express-code').val();
      const express_name = $(this).parents('tr').find('.express-code option:selected').text();
      const express_number = $(this).parents('tr').find('.express-number').val();

      $(this).parent().siblings('.edit-shipment').removeClass('d-none');
      $(this).parent().addClass('d-none');

      $(this).parents('tr').find('.edit-show').removeClass('d-none');
      $(this).parents('tr').find('.edit-form').addClass('d-none');

      $http.put(`/orders/{{ $order->id }}/shipments/${id}`, {express_code,express_name,express_number}).then((res) => {
        layer.msg(res.message);
        window.location.reload();
      })
    });

    let app = new Vue({
      el: '#app',

      data: {
        statuses: @json($statuses ?? []),
        form: {
          status: "",
          express_number: '',
          express_code: '',
          notify: 0,
          comment: '',
        },

        source: {
          express_company: @json(system_setting('base.express_company', [])) || [],
        },

        rules: {
          status: [{required: true, message: 'Vui lòng chọn trạng thái', trigger: 'blur'}, ],
        },

        @hook('admin.order.form.vue.data')
      },

      methods: {
        submitForm(form) {
          this.$refs[form].validate((valid) => {
            if (!valid) {
              layer.msg('Vui lòng kiểm tra lại biểu mẫu.',()=>{});
              return;
            }

            $http.put(`/orders/{{ $order->id }}/status`,this.form).then((res) => {
              layer.msg(res.message);
              window.location.reload();
            })
          });
        },

        @hook('admin.order.form.vue.methods')
      },

      @hook('admin.order.form.vue.options')
    })
  </script>
  @endcan
@endpush

