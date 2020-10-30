<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { mapGetters, mapMutations } from 'vuex';
import AppBreadcrumbs from '@/components/App/AppBreadcrumbs.vue';
import AppLogo from '@/components/App/AppLogo';
import NavMenu from '@/components/App/NavMenu';

export default {
  components: {
    AppBreadcrumbs,
    AppLogo,
    NavMenu,
  },

  computed: {
    ...mapGetters([
      'mainmenu',
    ]),
    showSidebar() { return this.$route.matched.reduce((acc, item) => acc || !!item.components.right, false); },
  },

  methods: {
    ...mapMutations({
      toggleRightSidebar: 'toggleRightSidebar',
    }),
  },
};
</script>

<template>
  <div class="AppNavbar bg-primary text-white">
    <q-toolbar>
      <q-btn class="q-mr-sm lt-md"
        flat
        round
        icon="menu"
        @click="$emit('toggle-left-drawer')"
      />
      <AppLogo class="self-stretch"/>
      <q-separator class="gt-sm" dark vertical inset=""/>
      <NavMenu class="gt-sm" :items="mainmenu"/>
      <q-space/>
      <q-btn class="q-mr-sm lt-md"
        v-if="showSidebar"
        flat
        round
        icon="menu_open"
        @click="toggleRightSidebar"
      />
    </q-toolbar>
    <AppBreadcrumbs/>
  </div>
</template>

<style lang="scss">
.AppNavbar {
  a {
    color: inherit;
  }
}
</style>
