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

  data: () => ({
    left      : false,
    right     : false,
  }),

  computed: {
    ...mapGetters([
      'loading',
      'lsEnabled',
      'rsEnabled',
    ]),
  },
};
</script>

<template>
    <QLayout view="hHh LpR lff" id="app">

      <QHeader reveal elevated class="bg-primary text-white">
        <AppNavbar @toggle-left-drawer="left = !left"/>
      </QHeader>

      <QDrawer v-model="left" side="left" overlay bordered v-if="lsEnabled">
        <!-- drawer content -->
      </QDrawer>

      <QDrawer  v-model="right" side="right" v-if="rsEnabled">
        <!-- drawer content -->
      </QDrawer>

      <QPageContainer>
          <router-view/>
      </QPageContainer>

      <AppFooter/>

    </QLayout>
</template>
