<!--
  WofhTools
  File: RadioButton.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'RadioButton',
  inheritAttrs: false,

  model: {
    prop: 'value',
    event: 'change',
  },

  props: {
    id: { type: String, default: null },
    name: String,
    value: { type: [String, Boolean, Number], default: null },
    checked: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    theme: String,
  },

  data: () => ({
    propChecked: null,
  }),


  computed: {
    classes() {
      let cls = '';
      if (this.theme) cls += ' radio_theme_' + this.theme;
      if (this.disabled) cls += ' radio_disabled';
      return cls;
    },

    isChecked() {
      if (this.value === null) {
        return this.propChecked;
      }
      return this.$attrs.value === this.value;
    },
  },

  mounted() {
    this.propChecked = this.checked;
  },

  watch: {
    // checked(v) { if (v !== this.isChecked) this.toggle(); },
  },

  methods: {
    updateState(e) {
      if (e.target.checked) {
        this.propChecked = true;
        this.$emit('change', this.$attrs.value);
      }
    },
  },
};
</script>

<template>
  <div>
    <div :class="classes" class="radio">
      <label class="radio__label">
        <input
          :checked="isChecked"
          :disabled="disabled"
          :id="id"
          :name="name"
          :value="value"
          @change="updateState"
          class="radio__element"
          type="radio"
          v-bind="$attrs"
        >
        <span class="radio__box">
          <span class="radio__indicator">
            <i class="radio__tick"></i>
          </span>
        </span>
        <span class="radio__text" v-if="$slots.default"><slot></slot></span>
      </label>
    </div>
  </div>
</template>
