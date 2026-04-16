@hookwrapper('checkout.body.address')
<template id="address-dialog">
  <div class="address-dialog">
    <el-dialog custom-class="mobileWidth" title="{{ __('address.index') }}" :visible.sync="editShow" @close="closeAddressDialog('addressForm')" :close-on-click-modal="false">
      <el-form ref="addressForm" :rules="rules" label-position="top" :model="form" label-width="100px">
        <div class="d-flex flex-column flex-sm-row flex-wrap mr-hoa-name-email-row">
          <el-form-item label="{{ __('address.name') }}" class="flex-grow-1 mr-hoa-form-item-name" prop="name">
            <el-input v-model="form.name" placeholder="{{ __('address.name') }}"></el-input>
          </el-form-item>
          @if (!current_customer())
          <el-form-item label="{{ __('common.email') }}" prop="email" v-if="type == 'guest_shipping_address' || !shippingRequired" class="flex-grow-1 mr-hoa-form-item-email">
            <el-input v-model="form.email" placeholder="{{ __('common.email') }}"></el-input>
          </el-form-item>
          @endif
        </div>
        <el-form-item label="{{ __('address.address_2') }}">
          <el-input v-model="form.address_2" placeholder="{{ __('address.address_2') }}"></el-input>
        </el-form-item>
        <div class="d-flex dialog-address">
          <el-form-item label="{{ __('address.phone') }}" class="w-100">
            <el-input maxlength="11" v-model="form.phone" type="number" placeholder="{{ __('address.phone') }}"></el-input>
          </el-form-item>
        </div>
        <input type="hidden" v-model="form.country_id">
        <div class="d-flex flex-column flex-lg-row mr-hoa-address-fields-row">
          <el-form-item prop="zone_id" label="Tỉnh/Thành phố *" class="mr-hoa-address-field">
            <el-select v-model="form.zone_id" class="w-100" filterable placeholder="Chọn một tùy chọn…">
              <el-option v-for="item in source.zones" :key="item.id" :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="city" label="Quận huyện *" required class="mr-hoa-address-field">
            <el-input v-model="form.city" placeholder="Chọn quận/huyện"></el-input>
          </el-form-item>
          <el-form-item prop="address_1" label="Địa chỉ *" class="mr-hoa-address-field">
            <el-input v-model="form.address_1" placeholder="{{ __('address.address_1') }}"></el-input>
          </el-form-item>
        </div>
        <el-form-item label="" v-if="source.isLogin">
          <span class="me-2">{{ __('address.default') }}</span> <el-switch v-model="form.default"></el-switch>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="addressFormSubmit('addressForm')">{{ __('common.save') }}</el-button>
          <el-button @click="closeAddressDialog('addressForm')">{{ __('common.cancel') }}</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
  Vue.component('address-dialog', {
  template: '#address-dialog',
  props: {
    value: {
      default: null
    },
  },

  data: function () {
    return {
      editShow: false,
      index: null,
      type: 'shipping_address_id',
      shippingRequired: true,
      form: {
        name: '',
        email: '',
        phone: '',
        country_id: @json((int) system_setting('base.country_id')),
        zipcode: '',
        zone_id: @json((int) system_setting('base.zone_id')),
        city: '',
        address_1: '',
        address_2: '',
        default: false,
      },

      rules: {
        name: [{
          required: true,
          message: '{{ __('shop/account/addresses.enter_name') }}',
          trigger: 'blur'
        }, ],
        email: [{
          required: false,
          type: 'email',
          message: '{{ __('shop/login.email_err') }}',
          trigger: 'blur'
        }, ],
        address_1: [{
          required: true,
          message: ' {{ __('shop/account/addresses.enter_address') }}',
          trigger: 'blur'
        }, ],
        zone_id: [{
          required: false,
          message: '{{ __('shop/account/addresses.select_province') }}',
          trigger: 'blur'
        }, ],
        city: [{
          required: true,
          message: 'Vui lòng nhập quận/huyện',
          trigger: 'blur'
        }, ],
      },

      source: {
        countries: @json($countries ?? []),
        zones: [],
        isLogin: config.isLogin,
      },
    }
  },

  computed: {
  },

  beforeMount() {
    this.countryChange(this.form.country_id);
  },

  methods: {
    buildDefaultForm() {
      return {
        name: '',
        email: '',
        phone: '',
        country_id: @json((int) system_setting('base.country_id')),
        zipcode: '',
        zone_id: @json((int) system_setting('base.zone_id')),
        city: '',
        address_1: '',
        address_2: '',
        default: false,
      };
    },

    normalizeAddressInput(addresses) {
      if (!addresses) return this.buildDefaultForm();

      let parsed = addresses;
      if (typeof parsed === 'string') {
        try {
          parsed = JSON.parse(parsed);
        } catch (e) {
          parsed = {};
        }
      }
      if (typeof parsed !== 'object' || Array.isArray(parsed)) {
        parsed = {};
      }

      return Object.assign(this.buildDefaultForm(), parsed);
    },

    editAddress(addresses, type, shippingRequired = true) {
      this.type = type
      this.form = this.normalizeAddressInput(addresses)

      this.countryChange(this.form.country_id);
      this.shippingRequired = shippingRequired;
      this.editShow = true
    },

    addressFormSubmit(form) {
      this.$refs[form].validate((valid) => {
        if (!valid) {
          this.$message.error('{{ __('shop/checkout.check_form') }}');
          return;
        }

        this.$emit('change', this.form)
      });
    },

    closeAddressDialog() {
      if (this.$refs['addressForm']) {
        this.$refs['addressForm'].resetFields();
      }
      this.editShow = false
      this.form = this.buildDefaultForm();
      this.source.zones = [];
    },

    countryChange(e) {
      const countryId = parseInt(e, 10);
      if (!Number.isFinite(countryId) || countryId <= 0) {
        this.source.zones = [];
        this.form.zone_id = '';
        return;
      }

      $http.get(`/countries/${countryId}/zones`, null, {
        hload: true
      }).then((res) => {
        this.source.zones = res.data.zones;

        if (!res.data.zones.some(e => e.id == this.form.zone_id)) {
          this.form.zone_id = '';
        }
        if (!this.form.zone_id && res.data.zones.length) {
          this.form.zone_id = res.data.zones[0].id;
        }
      })
    },
  }
});
</script>
@endhookwrapper
