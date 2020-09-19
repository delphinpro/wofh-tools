<!--
  WofhTools
  Alert.vue
  (c) 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'Alert',

  components: {
  },

  props: {
    type: { type: String, default: 'default' },
    title: { type: String },
    icon: { type: String },
    dismiss: { type: Boolean, default: false },
    callout: { type: Boolean, default: false },
  },

  data: () => ({
    isVisible: true,
  }),


  computed: {
    baseClass() {
      return {
        alert_info: this.type === 'info',
        alert_success: this.type === 'success',
        alert_warning: this.type === 'warning',
        alert_danger: this.type === 'danger',
        alert_callout: this.callout,
        alert_dismissable: this.dismissable,
      };
    },
    dismissable() {
      return this.dismiss && !this.callout;
    },
  },

  methods: {
    hideMe() {
      this.isVisible = false;
    },
  },
};
</script>

<template>
  <transition name="fade">
    <div class="alert" :class="baseClass" v-if="isVisible">
<!--      <CloseButton class="alert__close" :theme="type" v-if="dismissable" @click="hideMe"/>-->
      <div class="alert__header" v-if="title">
<!--        <FaIcon class="alert__icon" :name="icon" v-if="icon"/>-->
        <span class="alert__title" v-text="title"></span>
      </div>
      <div class="alert__content" v-if="$slots.default">
        <slot></slot>
      </div>
    </div>
  </transition>
</template>
