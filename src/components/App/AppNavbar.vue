<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { mapGetters } from 'vuex';
import AppBreadcrumbs from '@/components/App/AppBreadcrumbs.vue';
import AppLogo from '@/components/App/AppLogo';
import NavMenu from '@/components/App/NavMenu';
// import UserMenu from '@/components/App/UserMenu';

export default {
  components: {
    AppBreadcrumbs,
    AppLogo,
    NavMenu,
    // UserMenu,
  },

  computed: {
    ...mapGetters([
      'mainmenu',
      // 'getProfile',
      // 'isAuth',
      // 'isProfileLoaded',
    ]),
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
      <!--    <UserMenu/>-->
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
