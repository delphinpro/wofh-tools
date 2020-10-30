<!--
  WofhTools
  Layout: StatLayout.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'StatLayout',

  data: () => ({}),

  computed: {
    right: {
      get() { return this.$store.state.common.rightSidebarModel; },
      set(val) { this.$store.commit('toggleRightSidebar', val); },
    },
    showSidebar() {
      return this.$route.matched.reduce((acc, item) => acc || !!item.components.right, false);
    },
  },
};
</script>

<template>
  <div>
    <router-view></router-view>

    <q-drawer
      v-model="right"
      side="right"
      bordered
      show-if-above
      v-if="showSidebar"
    >
      <router-view name="right"/>
    </q-drawer>
  </div>
</template>
