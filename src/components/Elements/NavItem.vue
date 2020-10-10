<!--
  WofhTools
  Component: NavItem.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'NavItem',

  props: {
    title  : { type: String, required: true },
    caption: { type: String, default: '' },
    icon   : { type: String, default: '' },
    route  : { type: Object, default: null },
  },

};
</script>

<template>
  <q-item :to="route">
    <q-item-section v-if="icon" avatar>
      <q-icon :name="icon"/>
    </q-item-section>
    <q-item-section>
      <q-item-label>{{ title }}</q-item-label>
      <q-item-label caption v-if="caption">{{ caption }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<style>
.q-item.q-router-link--active,
.q-item--active {
  color: currentColor;
}

.q-item.q-router-link--exact-active,
.q-item--exact-active {
  color: #3c8dbc;
  color: var(--q-color-primary);
}
</style>
