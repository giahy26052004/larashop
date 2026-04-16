@extends('admin::layouts.master')

@section('title', 'Sản phẩm')

@section('content')
  @if ($errors->has('error'))
    <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  @if (session()->has('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif

  <div id="product-app">
    <div class="card h-min-600">
      <div class="card-body">
        <div class="bg-light p-4">
          <div class="row">
            <div class="col-xxl-20 col-xl-4 col-lg-5 col-md-6 d-flex align-items-center mb-3">
              <label class="filter-title">Tên sản phẩm</label>
              <input @keyup.enter="search" type="text" v-model="filter.name" class="form-control"
                     placeholder="Tên sản phẩm">
            </div>
          </div>

          <div class="row">
            <label class="filter-title"></label>
            <div class="col-auto">
              <button type="button" @click="search"
                      class="btn btn-outline-primary btn-sm">Lọc</button>
              <button type="button" @click="resetSearch"
                      class="btn btn-outline-secondary btn-sm">Đặt lại</button>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between my-4 flex-wrap gap-2">
          @if ($type != 'trashed')
            <a href="{{ admin_route('products.create') }}" class="me-1 nowrap">
              <button class="btn btn-primary">Thêm sản phẩm</button>
            </a>
            @if ($products->total())
              <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                        @click="batchDelete">Xóa đã chọn</button>
                <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                        @click="batchActive(true)">Bật đã chọn</button>
                <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                        @click="batchActive(false)">Tắt đã chọn</button>
                @hook('admin.product.batch_btns.after')
              </div>
            @endif
          @else
            @if ($products->total())
                <button class="btn btn-primary" @click="clearRestore">Xóa vĩnh viễn</button>
              <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                      @click="batchDelete">Xóa đã chọn</button>
              <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                      @click="batchActive(true)">Bật đã chọn</button>
              <button class="btn btn-outline-secondary" :disabled="!selectedIds.length"
                      @click="batchActive(false)">Tắt đã chọn</button>
              @hook('admin.product.batch_btns.after')
            @endif
          @endif
        </div>

        @if ($products->total())
          <div class="table-push">
            <table class="table table-hover">
              <thead>
              <tr>
                <th><input type="checkbox" v-model="allSelected"/></th>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá (VND)</th>
                <th>Số lượng</th>
                @if ($type != 'trashed')
                  <th>Trạng thái</th>
                @endif
                @hook('admin.product.list.column')
                <th class="text-end">Hành động</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($products_format as $product)
                <tr>
                  <td><input type="checkbox" :value="{{ $product['id'] }}" v-model="selectedIds"/></td>
                  <td>{{ $product['id'] }}</td>
                  <td>
                    <div class="wh-60 border d-flex rounded-2 justify-content-center align-items-center"><img
                        src="{{ $product['images'][0] ?? 'image/placeholder.png' }}" class="img-fluid max-h-100"></div>
                  </td>
                  <td>
                    <a href="{{ $product['url'] }}" target="_blank" title="{{ $product['name'] }}"
                       class="text-dark">{{ $product['name'] }}</a>
                  </td>
                  <td>{{ $product['price_formatted'] }}</td>
                  <td>{{ $product['quantity'] }}</td>
                  @if ($type != 'trashed')
                    <td>
                      <div class="form-check form-switch">
                        @php
                          $checked = $product['active'] ? 'checked' : '';
                          $active =  $product['active'] ? true : false;
                        @endphp
                        <input class="form-check-input cursor-pointer" type="checkbox" role="switch"
                               data-active="{{ $active }}" data-id="{{ $product['id'] }}"
                               @change="turnOnOff($event)" {{ $checked }}>
                      </div>
                    </td>
                  @endif
                  @hook('admin.product.list.column_value', $product)
                  <td class="text-end text-nowrap">
                    @if ($product['deleted_at'] == '')
                      <a href="{{ admin_route('products.edit', [$product['id']]) }}"
                         class="btn btn-outline-secondary btn-sm">Sửa</a>
                      <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm"
                         @click.prevent="deleteProduct({{ $loop->index }})">Xóa</a>
                      @hook('admin.product.list.action', $product)
                    @else
                      <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm"
                         @click.prevent="restoreProduct({{ $loop->index }})">Khôi phục</a>
                      @hook('admin.products.trashed.action', $product)
                    @endif
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>

          {{ $products->withQueryString()->links('admin::vendor/pagination/bootstrap-4') }}
        @else
          <x-admin-no-data/>
        @endif
      </div>
    </div>
  </div>

  @hook('admin.product.list.content.footer')
@endsection

@push('footer')
  <script>
    @hook('product.detail.script.before')
    let app = new Vue({
      el: '#product-app',
      data: {
        url: '{{ $type == 'trashed' ? admin_route("products.trashed") : admin_route("products.index") }}',
        filter: {
          name: bk.getQueryString('name'),
          page: bk.getQueryString('page'),
          category_id: bk.getQueryString('category_id'),
          sku: bk.getQueryString('sku'),
          model: bk.getQueryString('model'),
          active: bk.getQueryString('active'),
          sort: bk.getQueryString('sort', ''),
          order: bk.getQueryString('order', ''),
        },
        selectedIds: [],
        productIds: @json($products->pluck('id')),
        @hook('admin.product.list.vue.data')
      },

      computed: {
        allSelected: {
          get(e) {
            return this.selectedIds.length == this.productIds.length;
          },
          set(val) {
            return val ? this.selectedIds = this.productIds : this.selectedIds = [];
          }
        },
        @hook('admin.product.list.vue.computed')
      },

      created() {
        bk.addFilterCondition(this);
        @hook('admin.product.list.vue.created')
      },

      methods: {
        turnOnOff(event) {
          let id = event.currentTarget.getAttribute("data-id");
          let checked = event.currentTarget.getAttribute("data-active");
          let type = true;
          if (checked) type = false;
          $http.post('products/status', {ids: [id], status: type}).then((res) => {
            layer.msg(res.message)
          })
        },

        batchDelete() {
          this.$confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.delete('products/delete', {ids: this.selectedIds}).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          }).catch(() => {
          });
        },

        batchActive(type) {
          this.$confirm('Bạn có chắc chắn muốn thay đổi trạng thái của sản phẩm đã chọn?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.post('products/status', {ids: this.selectedIds, status: type}).then((res) => {
              layer.msg(res.message)
              location.reload();
            })
          }).catch(() => {
          });
        },

        search() {
          this.filter.page = '';
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        checkedOrderBy(orderBy) {
          this.filter.sort = orderBy.split(':')[0];
          this.filter.order = orderBy.split(':')[1];
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        resetSearch() {
          this.filter = bk.clearObjectValue(this.filter)
          location = bk.objectToUrlParams(this.filter, this.url)
        },

        deleteProduct(index) {
          const id = this.productIds[index];

          this.$confirm('Bạn có chắc chắn muốn xóa sản phẩm này?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.delete('products/' + id).then((res) => {
              this.$message.success(res.message);
              location.reload();
            })
          }).catch(() => {
          });
          ;
        },

        restoreProduct(index) {
          const id = this.productIds[index];

          this.$confirm('Bạn có chắc chắn muốn khôi phục sản phẩm này?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.put('products/restore', {id: id}).then((res) => {
              location.reload();
            })
          }).catch(() => {
          });
          ;
        },

        clearRestore() {
          this.$confirm('Bạn có chắc chắn muốn xóa vĩnh viễn các sản phẩm đã xóa?', 'Lưu ý', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.post('products/trashed/clear').then((res) => {
              location.reload();
            })
          }).catch(() => {
          });
          ;
        },

        @hook('admin.product.list.vue.methods')
      },

      @hook('admin.product.list.vue.options')
    });

    @hook('admin.product.list.script.after')
  </script>
@endpush
