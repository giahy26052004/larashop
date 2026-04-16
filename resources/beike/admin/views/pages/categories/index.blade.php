@extends('admin::layouts.master')

@section('title', 'Danh mục')

@section('body-class', 'page-categories')

@section('content')
  @if ($errors->has('error'))
  <x-admin-alert type="danger" msg="{{ $errors->first('error') }}" class="mt-4"/>
  @endif

  @if (session()->has('success'))
  <x-admin-alert type="success" msg="{{ session('success') }}" class="mt-4"/>
  @endif

  <div id="category-app" class="card h-min-600">
    <div class="card-body">
      @hook('admin.categories.index.content.before')

      <div class="d-flex">
        @hook('admin.categories.index.top_buttons.before')
        <a href="{{ admin_route('categories.create') }}" class="btn btn-primary">Thêm danh mục</a>
        @hook('admin.categories.create.after')
      </div>

      @hook('admin.categories.list.before')
      <div class="mt-4 categories-wrap" style="" v-if="categories.length">
        <el-tree :data="categories" node-key="id" ref="tree">
          <div class="custom-tree-node d-flex align-items-center justify-content-between w-100" slot-scope="{ node, data }">
            <div><span>@{{ data.id }} - @{{ data.name }}</span></div>
            <div class="d-flex align-items-center">
              <span class="me-4 badge bg-success" v-if="data.active">Đang bật</span>
              <span class="me-4 badge bg-secondary" v-else>Đang tắt</span>
              <div>
                <button class="btn btn-outline-secondary btn-sm" @click.stop="toEdit(data.url_edit)">Sửa</button>
                <button class="btn btn-outline-danger btn-sm" @click.stop="removeCategory(node, data)">Xóa</button>
                @hook('admin.categories.delete.after')
              </div>
            </div>
          </div>
        </el-tree>
      </div>
      <div v-else><x-admin-no-data /></div>

      @hook('admin.categories.index.content.after')
    </div>
  </div>
@endsection

@push('footer')
  <script>
    @hook('admin.categories.index.script.before')

    let category_app = new Vue({
      el: '#category-app',
      data: {
        categories: [],
        rawCategories: @json($categories),
        defaultProps: {
          children: 'children',
          label: 'name'
        },
        @hook('admin.categories.index.vue.data')
      },

      created() {
        this.categories = (this.rawCategories || [])
          .filter(item => !item.parent_id)
          .map(item => ({...item, children: []}));
      },

      methods: {
        removeCategory(node, data) {
          this.$confirm('Bạn có chắc chắn muốn xóa danh mục này?', 'Xác nhận xóa', {
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
            type: 'warning'
          }).then(() => {
            $http.delete(`/categories/${data.id}`).then((res) => {
              layer.msg(res.message);
              this.$refs.tree.remove(data.id)
            })
          }).catch(()=>{})
        },

        toEdit(url) {
          window.location.href = url
        },

        @hook('admin.categories.index.vue.methods')
      },

      @hook('admin.categories.index.vue.options')
    });

    @hook('admin.categories.index.script.after')
  </script>
@endpush
