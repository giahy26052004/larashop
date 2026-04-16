@extends('admin::layouts.master')

@section('title', 'Đơn hàng')

@section('page-title-right')
  @if ($type != 'trashed')
    <button type="button" class="btn btn-outline-secondary btn-print" onclick="app.btnPrint()"><i
        class="bi bi-printer-fill"></i> In đơn hàng</button>
    @hook('admin.order.list.buttons')
  @endif
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
  @endphp

  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  <div id="orders-app" class="card h-min-600">
    <div class="card-body">
      @hook('admin.order.index.content.before')
      <div class="bg-light p-4 mb-3">
        <el-form :inline="true" ref="filterForm" :model="filter" class="demo-form-inline" label-width="100px">
          @hook('admin.order.index.content.filter.before')
          <div>
            <el-form-item label="Mã đơn hàng">
              <el-input @keyup.enter.native="search" v-model="filter.number" size="small"
                        placeholder="Mã đơn hàng"></el-input>
            </el-form-item>
            <el-form-item label="Tên khách">
              <el-input @keyup.enter.native="search" v-model="filter.customer_name" size="small"
                        placeholder="Tên khách">
              </el-input>
            </el-form-item>
            <el-form-item label="Email">
              <el-input @keyup.enter.native="search" v-model="filter.email" size="small"
                        placeholder="Email"></el-input>
            </el-form-item>
            <el-form-item label="Trạng thái" class="el-input--small">
              <select v-model="filter.status" class="form-select wp-100 bg-white bs-el-input-inner-sm">
                <option value="">Tất cả</option>
                @foreach ($statuses as $item)
                  <option value="{{ $item['status'] }}">{{ $item['name'] }}</option>
                @endforeach
              </select>
            </el-form-item>
          </div>
          <el-form-item label="Ngày tạo">
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                              placeholder="Chọn ngày" @change="pickerDate(1)"
                              v-model="filter.start" style="width: 100%;">
              </el-date-picker>
            </el-form-item>
            <span>-</span>
            <el-form-item>
              <el-date-picker format="yyyy-MM-dd" value-format="yyyy-MM-dd" type="date" size="small"
                              placeholder="Chọn ngày" @change="pickerDate(0)"
                              v-model="filter.end" style="width: 100%;">
              </el-date-picker>
            </el-form-item>
          </el-form-item>
          @hook('admin.order.list.filter.after')
        </el-form>

        <div class="row">
          <label class="wp-100"></label>
          <div class="col-auto">
            @hook('admin.order.index.content.filter_buttons.before')
            <button type="button" @click="search"
                    class="btn btn-outline-primary btn-sm">Lọc</button>
            <button type="button" @click="exportOrder"
                    class="btn btn-outline-primary btn-sm ms-1">Xuất</button>
            <button type="button" @click="resetSearch"
                    class="btn btn-outline-secondary btn-sm ms-1">Đặt lại</button>
            @hook('admin.order.index.content.filter_buttons.after')
          </div>
        </div>
      </div>

      @if (count($orders))
        <div class="d-flex flex-wrap gap-2 mb-3">
          @if ($type != 'trashed')
            <button type="button" class="btn btn-outline-danger btn-sm" :disabled="!selectedIds.length"
                    @click="batchDelete">Xóa đã chọn</button>
          @else
            <button type="button" class="btn btn-outline-secondary btn-sm" :disabled="!selectedIds.length"
                    @click="batchRestore">Khôi phục đã chọn</button>
          @endif
          @hook('admin.order.batch_buttons.after')
        </div>

        <div class="table-push">
          <table class="table">
            <thead>
            <tr>
              <th><input type="checkbox" v-model="allSelected"/></th>
              <th>ID</th>
              <th>Mã đơn hàng</th>
              <th>Tên khách</th>
              <th>Phương thức thanh toán</th>
              <th>Trạng thái</th>
              <th>Tổng</th>
              @hook('admin.order.list.item.th.total.after')
              <th>Ngày tạo</th>
              <th>Ngày nhận hàng</th>
              <th>Hành động</th>
            </tr>
            </thead>
            <tbody>
            @if (count($orders))
              @foreach ($orders as $order)
                <tr data-hook-id="{{ $order->id }}">
                  <td><input type="checkbox" :value="{{ $order['id'] }}" v-model="selectedIds"/></td>
                  <td>{{ $order->id }}</td>
                  <td>{{ $order->number }}</td>
                  <td>{{ sub_string($order->customer_name ?: $order->shipping_customer_name ?: $order->payment_customer_name, 14) }}</td>
                  <td>{{ $order->payment_method_name }}</td>
                  <td>
                    <span class="badge {{ $statusClassMap[$order->status] ?? 'bg-secondary' }}">
                      {{ $order->status_format }}
                    </span>
                  </td>
                  <td>{{ currency_format($order->total, $order->currency_code, $order->currency_value) }}</td>
                  @hook('admin.order.list.item.td.total.after', $order)
                  <td>{{ $order->created_at }}</td>
                  @php
                    $deliveryAt = '-';
                    $orderCommentText = (string) ($order->comment ?? '');
                    if (preg_match('/^Giờ giao mong muốn:\s*(.+)$/mu', $orderCommentText, $m)) {
                      $deliveryAt = mrhoa_format_delivery_wish(trim($m[1]));
                    }
                  @endphp
                  <td>{{ $deliveryAt }}</td>
                  <td>
                    @if (!$order->deleted_at)
                      <a href="{{ admin_route('orders.show', [$order->id]) }}"
                         class="btn btn-outline-secondary btn-sm">Xem
                      </a>
                      <button type="button" data-id="{{ $order->id }}"
                              class="btn btn-outline-danger btn-sm delete-btn">Xóa</button>
                    @else
                      <button type="button" data-id="{{ $order->id }}"
                              class="btn btn-outline-secondary btn-sm restore-btn">Khôi phục</button>
                      @hook('admin.products.trashed.action', $order)
                    @endif

                    @hook('admin.order.list.action', $order)
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="10" class="border-0">
                  <x-admin-no-data/>
                </td>
              </tr>
            @endif
            </tbody>
          </table>
        </div>
        {{ $orders->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
      @else
        <x-admin-no-data/>
      @endif

      @hook('admin.order.index.content.after')
    </div>
  </div>

  @hook('admin.order.list.content.footer')
@endsection

@push('footer')
  <script>
    @hook('admin.orders.list.script.before')

    var app = new Vue({
      el: '#orders-app',
      data: {
        url: '{{ $type == 'trashed' ? admin_route("orders.trashed") : admin_route("orders.index") }}',
        exportUrl: @json(admin_route('orders.export')),
        selectedIds: [],
        orderIds: @json($orders->pluck('id')),
        btnPrintUrl: '',
        filter: {
          number: bk.getQueryString('number'),
          status: bk.getQueryString('status'),
          customer_name: bk.getQueryString('customer_name'),
          email: bk.getQueryString('email'),
          start: bk.getQueryString('start'),
          end: bk.getQueryString('end'),
        },

        @hook('admin.order.index.vue.data')
      },

      watch: {
        "filter.start": {
          handler(newVal, oldVal) {
            if (!newVal) {
              this.filter.start = ''
            }
          }
        },
        "filter.end": {
          handler(newVal, oldVal) {
            if (!newVal) {
              this.filter.end = ''
            }
          }
        },
        "selectedIds": {
          handler(newVal, oldVal) {
            this.btnPrintUrl = `{{ admin_route('orders.shipping.get') }}?selected=${newVal}`;
          }
        },

        @hook('admin.order.index.vue.watch')
      },

      computed: {
        allSelected: {
          get(e) {
            return this.selectedIds.length == this.orderIds.length;
          },
          set(val) {
            val ? this.selectedIds = this.orderIds : this.selectedIds = [];
            this.btnPrintUrl = `{{ admin_route('orders.shipping.get') }}?selected=${this.selectedIds}`;
            return val;
          }
        },

        @hook('admin.order.index.vue.computed')
      },

      created() {
        bk.addFilterCondition(this);
      },

      methods: {
        btnPrint() {
          if (!this.selectedIds.length) {
            return layer.msg('Vui lòng chọn đơn hàng để in.', () => {
            });
          }
          window.open(this.btnPrintUrl);
        },

        pickerDate(type) {
          if (this.filter.end && this.filter.start > this.filter.end) {
            if (type) {
              this.filter.start = ''
            } else {
              this.filter.end = ''
            }
          }
        },

        search() {
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        resetSearch() {
          this.filter = bk.clearObjectValue(this.filter)
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        exportOrder() {
          location = bk.objectToUrlParams(this.filter, this.exportUrl)
        },

        batchDelete() {
          if (!this.selectedIds.length) {
            return layer.msg('Vui lòng chọn ít nhất một đơn hàng.', () => {
            });
          }
          this.$confirm('Bạn có chắc chắn muốn xóa các đơn hàng đã chọn?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.delete('orders/delete', {ids: this.selectedIds}).then((res) => {
              layer.msg(res.message);
              location.reload();
            });
          }).catch(() => {
          });
        },

        batchRestore() {
          if (!this.selectedIds.length) {
            return layer.msg('Vui lòng chọn ít nhất một đơn hàng.', () => {
            });
          }
          this.$confirm('Khôi phục các đơn hàng đã chọn?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.put('orders/restore_batch', {ids: this.selectedIds}).then((res) => {
              layer.msg(res.message);
              location.reload();
            });
          }).catch(() => {
          });
        },
      }
    });
  </script>

  <script>
    $('.delete-btn').click(function (event) {
      const id = $(this).data('id');
      const self = $(this);

      layer.confirm('Bạn có chắc chắn muốn xóa đơn hàng này?', {
        title: "Lưu ý",
        btn: ['Hủy', 'Xác nhận'],
        area: ['400px'],
        btn2: () => {
          $http.delete(`orders/${id}`).then((res) => {
            layer.msg(res.message);
            window.location.reload();
          })
        }
      })
    });

    $('.restore-btn').click(function (event) {
      const id = $(this).data('id');

      $http.put(`orders/restore/${id}`).then((res) => {
        window.location.reload();
      })
    });
  </script>
@endpush
