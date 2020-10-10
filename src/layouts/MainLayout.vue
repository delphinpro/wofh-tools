<!--
  WofhTools
  Component: MainLayout.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapGetters } from 'vuex';
import AppFooter from '@/components/App/AppFooter';
import AppNavbar from '@/components/App/AppNavbar';
import Error404 from '@/pages/Error404';
import NavItem from '@/components/Elements/NavItem';

export default {
  name: 'MainLayout',

  components: {
    AppFooter,
    AppNavbar,
    Error404,
    NavItem,
  },

  data: () => ({
    left : false,
    right: false,
  }),

  computed: {
    ...mapGetters(['showErrorPage', 'mainmenu']),
    rsEnabled() {
      return false;//!!this.$route.matched[this.$route.matched.length - 1].components.right;
    },
  },
};
</script>

<template>
  <q-layout view="hHh LpR lff">
    <q-header reveal elevated class="bg-primary text-white">
      <AppNavbar @toggle-left-drawer="left=!left"/>
    </q-header>

    <q-drawer
      v-model="left"
      side="left"
      overlay
      elevated
      behavior="mobile"
      :breakpoint="599"
    >
      <nav v-if="mainmenu.length">
        <q-list>
          <q-item-label header class="text-grey-8">Главное меню</q-item-label>
          <NavItem
            v-for="item in mainmenu"
            :key="item.title"
            :title="item.title"
            v-bind="item"
          />
        </q-list>
      </nav>
    </q-drawer>

    <q-drawer v-model="right" side="right" v-if="rsEnabled">
      <router-view name="right"/>
    </q-drawer>

    <q-page-container>
      <Error404 v-if="showErrorPage"/>
      <router-view v-else/>
    </q-page-container>

    <AppFooter/>

  </q-layout>
</template>
