<!--
  WofhTools
  Layout: MainLayout.vue
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
    left: false,

    home: {
      title: 'Главная',
      icon : 'home',
      route: { path: '/' },
    },
  }),

  computed: {
    ...mapGetters(['showErrorPage']),
    ...mapGetters({
      menu: 'mainmenu',
    }),
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
      <nav v-if="menu.length">
        <q-list>
          <q-item-label header class="text-grey-7">Главное меню</q-item-label>
          <NavItem :title="home.title" v-bind="home"/>
          <NavItem
            v-for="item in menu"
            :key="item.title"
            :title="item.title"
            v-bind="item"
          />
        </q-list>
      </nav>
    </q-drawer>

    <q-page-container>
      <Error404 v-if="showErrorPage"/>
      <router-view v-else/>
    </q-page-container>

    <AppFooter/>

  </q-layout>
</template>
