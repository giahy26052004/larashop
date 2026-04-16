@extends('admin::layouts.master')

@section('title', 'Danh mục')

@section('content')
  <div id="category-app" class="card">
    <div class="card-header">{{ $category->id ? 'Chỉnh sửa danh mục' : 'Thêm danh mục' }}</div>
    <div class="card-body">
      @hook('admin.categories.form.before')

      <form class="needs-validation" novalidate action="{{ admin_route($category->id ? 'categories.update' : 'categories.store', $category) }}"
        method="POST">
        @csrf
        @method($category->id ? 'PUT' : 'POST')
        <input type="hidden" name="_redirect" value="{{ $_redirect }}">

        @if (session('success'))
          <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
        @endif

        @hook('admin.category.form.before')

        <x-admin-form-input
          name="descriptions[{{ locale() }}][name]"
          title="Tên"
          :value="old('descriptions.' . locale() . '.name', collect($descriptions)->get(locale())->name ?? '')"
          :required="true"
        />
        @hook('admin.product.categories.edit.name.after')
        <x-admin-form-textarea
          name="descriptions[{{ locale() }}][content]"
          title="Mô tả"
          :value="old('descriptions.' . locale() . '.content', collect($descriptions)->get(locale())->content ?? '')"
        />
        @hook('admin.product.categories.edit.content.after')
        <x-admin-form-input name="position" title="Thứ tự" :value="old('position', $category->position ?? 0)" />

        @hook('admin.categories.form.name.after')

        <x-admin-form-image :is-remove="true" name="image" title="Ảnh danh mục" :value="old('image', $category->image ?? '')">
          <div class="help-text font-size-12 lh-base">Kích thước đề nghị 300*300</div>
        </x-admin-form-image>

        @hook('admin.categories.form.image.after')

        <x-admin::form.row title="Danh mục cha">
          <input type="hidden" name="parent_id" value="0">
          <div class="form-control short wp-400 bg-light">Danh mục cấp 1 (không có danh mục cha).</div>
        </x-admin::form.row>

        @hook('admin.categories.form.parent.after')

        @hook('admin.category.form.after')

        <x-admin-form-switch title="Trạng thái" name="active" :value="old('active', $category->active ?? 1)" />

        @hook('admin.categories.form.switch.after')

        <x-admin::form.row>
          <button type="submit" class="btn btn-primary w-min-100 btn-lg mt-3">Lưu</button>
          @hook('admin.categories.form.submit.after')
        </x-admin::form.row>
      </form>

      @hook('admin.categories.form.after')
    </div>
  </div>
@endsection
