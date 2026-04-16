<div v-if="products.length">
  <div class="mb-product-wrap">
    <div v-for="product, index in products" :key="index" :class="['mb-product-list', product.selected ? 'active' : '']">
      <div class="d-flex align-items-center product-img">
        <input class="form-check-input" type="checkbox" @change="checkedCartTr(index)" v-model="product.selected">
        <div class="border d-flex align-items-center justify-content-center wh-80 ms-3"><img :src="product.image_url" :alt="product.name"
            class="img-fluid"></div>
      </div>
      <div class="product-mb-info ms-2">
        <div>
          <a class="name text-truncate-2 mb-1 text-black fw-bold" :href="'products/' + product.product_id"
            v-text="product.name"></a>
            @hook('cart.product.name.after')
          <div class="text-size-min text-muted mb-1">@{{ product.variant_labels }}</div>
          <div class="price fw-bold text-primary" v-html="product.price_format"></div>
        </div>

        <div class="d-flex justify-content-between">
          <div class="quantity-wrap-line">
            <div class="right"><i class="bi bi-chevron-down"></i></div>
            <input type="text" class="form-control" @input="quantityChange(product.quantity, product.cart_id, product.sku_id)" onkeyup="this.value=this.value.replace(/\D/g,'')" v-model.number="product.quantity" name="quantity" minimum="1">
            <div class="right"><i class="bi bi-chevron-up"></i></div>
          </div>

          <div class="text-end">
            <button type="button" class="btn text-secondary btn-sm px-0" @click.stop="checkedBtnDelete(product.cart_id)">
              <i class="bi bi-x-lg"></i> {{ __('common.delete') }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="cart-mb-total flex-column align-items-stretch">
    <div class="small text-muted mb-2 px-2">
      <div class="d-flex justify-content-between"><span>{{ __('shop/carts.subtotal') }}</span><span>@{{ amount_format }}</span></div>
      <div class="d-flex justify-content-between"><span>@{{ shipping_line_title }}</span><span>@{{ cart_shipping_estimate_format }}</span></div>
      <div class="d-flex justify-content-between fw-bold mt-1 pt-1 border-top"><span>{{ __('shop/carts.order_total_estimated') }}</span><span>@{{ cart_grand_total_estimated_format }}</span></div>
    </div>
    <div class="d-flex w-100 justify-content-between align-items-center">
    <div class="left">
      <label for="check-all">
        <input class="form-check-input" type="checkbox" value="" id="check-all" v-model="allSelected"> {{ __('shop/carts.selected') }}(@{{ total_quantity }})
      </label>
    </div>
    <div class="right">
      <span class="total-price fw-bold">@{{ cart_grand_total_estimated_format }}</span>
      @hookwrapper('cart.confirm')
      <button type="button" class="btn btn-primary btn-checkout fs-5 fw-bold" @click="checkedBtnToCheckout">{{
        __('shop/carts.to_checkout') }}</button>
      @endhookwrapper
    </div>
    </div>
  </div>
</div>

<div v-else class="d-flex justify-content-center align-items-center flex-column">
  <div class="empty-cart-wrap text-center my-5">
    <div class="empty-cart-icon mb-3">
      <i class="bi bi-cart fs-1"></i>
    </div>
    <div class="empty-cart-text mb-3">
      <h5>{{ __('shop/carts.cart_empty') }}</h5>
      <p class="text-muted">{{ __('shop/carts.go_buy') }}</p>
    </div>
    <div class="empty-cart-action">
      <a href="{{ shop_route('home.index') }}" class="btn btn-primary">{{ __('shop/carts.go_shopping') }}</a>
    </div>
  </div>
</div>