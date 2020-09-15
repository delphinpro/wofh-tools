<!--
  WofhTools
  Component: App.vue
  © 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>

import { mapGetters } from 'vuex';
import AppNavbar from '@/components/App/AppNavbar';
import AppFooter from '@/components/App/AppFooter';
import Loading from '@/components/Widgets/Loading';

export default {
  name: 'App',

  components: {
    AppNavbar,
    AppFooter,
    Loading,
  },

  data: () => ({}),

  computed: {
    ...mapGetters([
      'loading',
    ]),
  },
};
</script>

<template>
  <div class="app" id="app">
    <AppNavbar class="app__navbar"/>
    <router-view class="app__main"/>
    <AppFooter class="app__footer"/>
    <div class="app__loader" v-if="loading">
      <Loading/>
    </div>
  </div>
</template>
