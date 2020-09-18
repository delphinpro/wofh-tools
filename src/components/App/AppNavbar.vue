<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

// import { mapGetters, mapState } from 'vuex';
// import { AUTH_LOGOUT } from '@/store/modules/store-auth';
import AppLogo from '@/components/App/AppLogo';
import NavMenu from '@/components/App/NavMenu';
import UserMenu from '@/components/App/UserMenu';

import { matMenu } from '@quasar/extras/material-icons';
import { mapGetters } from 'vuex';

export default {
  components: {
    AppLogo,
    NavMenu,
    UserMenu,
  },

  data: () => ({
    matMenu,

    mainmenu: [
      { id: 1, title: 'Статистика', icon: null, path: '/stat' },
      // { id: 2, title: 'Науки', icon: null, path: '/technology' },
      // { id: 3, title: 'Квесты', icon: null, path: '/quests' },
      // { id: 4, title: 'Таблицы', icon: null, path: 'tables' },
    ],
  }),

  computed: {
    ...mapGetters([
      'lsEnabled',
      //     'getProfile',
      //     'isAuth',
      //     'isProfileLoaded',
    ]),
    // ...mapState({
    //     name: state => `${state.user.profile.title} ${state.user.profile.name}`,
    // }),
  },

  created() {
  },

  methods: {
    logout: function () {
      // this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
    },
  },
};
</script>

<template>
  <QToolbar class="app-navbar bg-primary text-white">
    <QBtn class="q-mr-sm"
      flat
      round
      :icon="matMenu"
      @click="$emit('toggle-left-drawer')"
      v-if="lsEnabled"
    />
    <AppLogo class="self-stretch"/>
    <QSeparator class="gt-sm" dark vertical inset=""/>
    <NavMenu class="gt-sm" :items="mainmenu"/>
    <QSpace/>
    <UserMenu/>
  </QToolbar>
</template>

<style lang="scss">
.app-navbar {
  a {
    color: inherit;
  }
}
</style>
