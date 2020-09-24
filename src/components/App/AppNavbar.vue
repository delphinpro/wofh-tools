<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

// import { mapGetters, mapState } from 'vuex';
// import { AUTH_LOGOUT } from '@/store/modules/store-auth';
import AppBreadcrumbs from '@/components/App/AppBreadcrumbs.vue';
import AppLogo from '@/components/App/AppLogo';
import NavMenu from '@/components/App/NavMenu';
import UserMenu from '@/components/App/UserMenu';

import { matMenu } from '@quasar/extras/material-icons';

export default {
  components: {
    AppBreadcrumbs,
    AppLogo,
    NavMenu,
    UserMenu,
  },

  props: {
    lsLeft: Boolean,
  },
  data : () => ({
    matMenu,

    mainmenu: [
      { id: 1, title: 'Статистика', icon: null, path: '/stat' },
      // { id: 2, title: 'Науки', icon: null, path: '/technology' },
      // { id: 3, title: 'Квесты', icon: null, path: '/quests' },
      // { id: 4, title: 'Таблицы', icon: null, path: 'tables' },
    ],
  }),

  computed: {
    // ...mapGetters([
    //     'getProfile',
    //     'isAuth',
    //     'isProfileLoaded',
    // ]),
    // ...mapState({
    //     name: state => `${state.user.profile.title} ${state.user.profile.name}`,
    // }),
  },

  methods: {
    logout: function () {
      // this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
    },
  },
};
</script>

<template>
  <div class="AppNavbar bg-primary text-white">
    <q-toolbar>
      <q-btn class="q-mr-sm"
        flat
        round
        :icon="matMenu"
        @click="$emit('toggle-left-drawer')"
        v-if="lsLeft"
      />
      <AppLogo class="self-stretch"/>
      <q-separator class="gt-sm" dark vertical inset=""/>
      <NavMenu class="gt-sm" :items="mainmenu"/>
      <!--    <q-space/>-->
      <!--    <UserMenu/>-->
    </q-toolbar>
    <AppBreadcrumbs v-if="$route.name!=='home'"/>
  </div>
</template>

<style lang="scss">
.AppNavbar {
  a {
    color: inherit;
  }
}
</style>
