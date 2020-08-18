<!--
  WofhTools
  File: InfoBox.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'InfoBox',

  props: {
    link: { type: [String, Object], default: null },
    theme: { type: String, default: null },
  },

  computed: {
    tag() { return this.link ? 'router-link' : 'div'; },
  },
};
</script>

<template>
  <component :class="theme ? 'info-box_theme_'+theme : ''" :is="tag" :to="link" class="info-box">
    <div class="info-box__icon">
      <slot name="info"></slot>
    </div>
    <div class="info-box__body">
      <div class="info-box__content">
        <slot name="default"></slot>
      </div>
      <div class="info-box__footer" v-if="$slots.footer">
        <slot name="footer"></slot>
      </div>
    </div>
  </component>
</template>
