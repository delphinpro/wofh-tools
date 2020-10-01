<!--
  WofhTools
  Component: NavMenu.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'NavMenu',

  props: {
    items: { type: Array, default: [] },
  },
};
</script>

<template>
  <div class="flex self-stretch" v-if="items.length">
      <div class="flex self-stretch" v-for="item in items" :key="item.id">
        <q-btn stretch flat no-caps :label="item.title" :to="item.path"/>
      </div>
  </div>
</template>
