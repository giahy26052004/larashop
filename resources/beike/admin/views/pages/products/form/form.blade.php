@extends('admin::layouts.master')

@section('title', $product->id ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm')

@section('body-class', 'page-product-form')

@section('content')
  @if (session()->has('success'))
    <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <form novalidate class="needs-validation"
            action="{{ $product->id ? admin_route('products.update', $product) : admin_route('products.store') }}"
            method="POST" id="product-form">
        @csrf
        @method($product->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}"/>

        <x-admin-form-input
          name="descriptions[{{ locale() }}][name]"
          title="Tên"
          value="{{ old('descriptions.' . locale() . '.name', optional(collect($descriptions)->get(locale()))->name ?? '') }}"
          required
        />

        <x-admin-form-textarea
          name="descriptions[{{ locale() }}][content]"
          title="Mô tả"
          value="{{ old('descriptions.' . locale() . '.content', optional(collect($descriptions)->get(locale()))->content ?? '') }}"
        />

        <x-admin::form.row title="Ảnh">
          <div class="d-flex align-items-center">
            <div class="open-file-manager border rounded-2 d-flex justify-content-center align-items-center me-3" style="width: 160px; height: 160px; cursor: pointer;">
              @if(old('images.0', $product->images[0] ?? ''))
                <img src="{{ image_origin(old('images.0', $product->images[0] ?? '')) }}" class="img-fluid" style="max-height: 100%; max-width: 100%;"/>
              @else
                <div class="text-center text-muted">
                  <i class="bi bi-plus fs-1"></i>
                  <div>Chọn ảnh</div>
                </div>
              @endif
            </div>
            <input type="hidden" name="images[]" value="{{ old('images.0', $product->images[0] ?? '') }}">
          </div>
          <div class="help-text mt-2">Kích thước nên là 300x300</div>
        </x-admin::form.row>

        <x-admin-form-select
          name="categories[]"
          title="Danh mục"
          :options="$source['categories']->toArray()"
          :value="old('categories', $category_ids)"
          key="id"
          label="name"
        />

        @if (!empty($flower_tag_attributes))
          <x-admin::form.row title="Tag lọc (shop hoa)">
            <div class="w-100">
              <div class="alert alert-info py-2 px-3 mb-3">
                Chọn giá trị tag cho sản phẩm. Để <strong>thêm / đổi tên / xóa</strong> tag: vào <strong>Sản phẩm → Tag lọc (thêm/sửa/xóa)</strong> (màn Thuộc tính), chọn nhóm <strong>Tag bộ lọc</strong> — mỗi dòng là một nhóm (Kiểu dáng, Loài hoa…); bấm vào thuộc tính để sửa các giá trị (Bó hoa, Giỏ hoa…).
              </div>
              @foreach ($flower_tag_attributes as $attribute)
                <div class="mb-3">
                  <div class="fw-bold mb-2">{{ $attribute['name'] }}</div>
                  <div class="d-flex flex-wrap gap-3">
                    @foreach ($attribute['values'] as $value)
                      @php
                        $selectedValues = old('tag_attribute_values.' . $attribute['id'], $selected_tag_value_ids[$attribute['id']] ?? []);
                      @endphp
                      <label class="form-check d-flex align-items-center me-3">
                        <input
                          class="form-check-input me-2"
                          type="checkbox"
                          name="tag_attribute_values[{{ $attribute['id'] }}][]"
                          value="{{ $value['id'] }}"
                          {{ in_array($value['id'], $selectedValues) ? 'checked' : '' }}
                        >
                        <span>{{ $value['name'] }}</span>
                      </label>
                    @endforeach
                  </div>
                </div>
              @endforeach
            </div>
          </x-admin::form.row>
        @endif

        <x-admin-form-input
          name="skus[0][sku]"
          title="SKU"
          :value="old('skus.0.sku', $product->skus[0]->sku ?? 'BKSKU-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 4) . '-' . substr(str_shuffle('0123456789'), 0, 3))"
          required
        />

        <x-admin-form-input
          name="skus[0][price]"
          type="number"
          title="Giá bán (VND)"
          group-right="{{ system_setting('base.currency') === 'VND' ? 'đ' : system_setting('base.currency') }}"
          :value="old('skus.0.price', $product->skus[0]->price ?? '')"
          step="1"
          required
        />

        <x-admin-form-input
          name="skus[0][origin_price]"
          type="number"
          title="Giá gốc (VND, trước khuyến mãi)"
          group-right="{{ system_setting('base.currency') === 'VND' ? 'đ' : system_setting('base.currency') }}"
          :value="old('skus.0.origin_price', $product->skus[0]->origin_price ?? '')"
          step="1"
        />
        <p class="text-muted small mb-3">Để trống hoặc bằng giá bán thì shop không hiện giá gạch ngang. Muốn hiện “đang giảm”: nhập <strong>giá gốc &gt; giá bán</strong> — % giảm do theme tự tính, không có ô nhập % riêng.</p>

        <input type="hidden" name="skus[0][cost_price]" value="{{ old('skus.0.cost_price', $product->skus[0]->cost_price ?? 0) }}">

        <x-admin-form-input
          name="skus[0][quantity]"
          type="number"
          title="Số lượng"
          :value="old('skus.0.quantity', $product->skus[0]->quantity ?? '')"
        />

        <input type="hidden" name="skus[0][variants]" value="">
        <input type="hidden" name="skus[0][position]" value="0">
        <input type="hidden" name="skus[0][is_default]" value="1">

        <x-admin-form-switch
          name="active"
          title="Trạng thái"
          :value="old('active', $product->active ?? 1)"
        />

        <div class="mt-4">
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </form>
    </div>
  </div>
@endsection
