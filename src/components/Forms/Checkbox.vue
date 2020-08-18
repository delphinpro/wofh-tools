<!--
  WofhTools
  File: Checkbox.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'Checkbox',
  inheritAttrs: false,

  model: {
    prop: 'model',
    event: 'change',
  },

  props: {
    id: { type: String, default: null },
    name: String,
    model: { type: [String, Array, Boolean], default: undefined },
    value: { type: [String, Boolean, Number], default: null },
    checked: { type: Boolean, default: false },
    required: Boolean,
    disabled: Boolean,
    theme: String,
  },

  data: () => ({
    propChecked: null,
  }),


  computed: {
    isChecked() {
      if (this.model === undefined && this.value === null) {
        return this.propChecked;
      }
      if (this.model === undefined) {
        return this.value;
      }
      if (Array.isArray(this.model)) {
        return this.model.indexOf(this.value) !== -1;
      }
      return this.model || this.value;
    },

    classes() {
      let cls = '';
      if (this.theme) cls += ' checkbox_theme_' + this.theme;
      if (this.disabled) cls += ' checkbox_disabled';
      return cls;
    },
  },

  mounted() {
    this.propChecked = this.checked;
    if (this.checked && !this.isChecked) {
      this.toggle();
    }
  },

  watch: {
    checked(v) { if (v !== this.isChecked) this.toggle(); },
  },

  methods: {
    toggle() {
      let value = this.model || this.value;
      if (Array.isArray(value)) {
        const i = value.indexOf(this.value);
        if (i === -1) value.push(this.value);
        else value.splice(i, 1);
      } else {
        this.propChecked = !this.propChecked;
        value = !this.isChecked;
      }
      this.$emit('change', value);
    },
  },
};
</script>

<template>
  <div :class="classes" class="checkbox">
    <label class="checkbox__label">
      <input
        :checked="isChecked"
        :disabled="disabled"
        :id="id"
        :name="name"
        :required="required"
        :value="value"
        @change="toggle"
        class="checkbox__element"
        type="checkbox"
        v-bind="$attrs"
      >
      <span class="checkbox__box">
        <span class="checkbox__indicator">
          <i :class="{checkbox__tick_checked:isChecked}" class="checkbox__tick"></i>
        </span>
      </span>
      <span class="checkbox__text" v-if="$slots.default"><slot></slot></span>
    </label>
  </div>
</template>
