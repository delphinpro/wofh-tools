<!--
  WofhTools
  Component: Box.vue
  (c) 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'Box',

  props: {
    type: { type: String, default: '' },
    title: { type: String },
    icon: { type: String, default: '' },
  },
};
</script>

<template>
  <div :class="(type ? 'box2_style_'+type : '')" class="box2">
    <div class="box2__header" v-if="title">
      <FaIcon
        class="box2__icon"
        :name="icon"
        v-if="icon"
      />
      <span class="box2__title">{{ title }}</span>
    </div>
    <div class="box2__body">
      <slot></slot>
    </div>
  </div>
</template>
