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


export default {
  components: {
    AppLogo,
    NavMenu,
    UserMenu,
  },

  data: () => ({
    mainmenu: [
      { id: 1, title: 'Статистика', icon: null, path: '/stat' },
      { id: 2, title: 'Науки', icon: null, path: '/technology' },
      { id: 3, title: 'Квесты', icon: null, path: '/quests' },
      { id: 4, title: 'Таблицы', icon: null, path: 'tables' },
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
  <header class="app-navbar">
    <div class="app-navbar__container">
      <AppLogo class="app-navbar__logo"/>
      <!--<button class="app-navbar__sidebar-toggle sidebar-toggle" type="button">
          <span class="fa fa-bars"></span>
          <span class="sr-only">Toggle navigation</span>
      </button>-->
      <NavMenu :items="mainmenu" class="app-navbar__mainmenu"/>
      <UserMenu class="app-navbar__usermenu"/>
    </div>
  </header>
</template>
