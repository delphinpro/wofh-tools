<!--
  WofhTools
  Component: MainLayout.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapGetters } from 'vuex';
import {
  matChat,
  matCode,
  matFavorite,
  matPublic,
  matRecordVoiceOver,
  matRssFeed,
  matSchool,
} from '@quasar/extras/material-icons';
import Error404 from '@/pages/Error404';
import EssentialLink from '@/components/EssentialLink.vue';
import AppFooter from '@/components/App/AppFooter';
import AppNavbar from '@/components/App/AppNavbar';

const linksData = [
  {
    title  : 'Docs',
    caption: 'quasar.dev',
    icon   : matSchool,
    link   : 'https://quasar.dev',
  },
  {
    title  : 'Github',
    caption: 'github.com/quasarframework',
    icon   : matCode,
    link   : 'https://github.com/quasarframework',
  },
  {
    title  : 'Discord Chat Channel',
    caption: 'chat.quasar.dev',
    icon   : matChat,
    link   : 'https://chat.quasar.dev',
  },
  {
    title  : 'Forum',
    caption: 'forum.quasar.dev',
    icon   : matRecordVoiceOver,
    link   : 'https://forum.quasar.dev',
  },
  {
    title  : 'Twitter',
    caption: '@quasarframework',
    icon   : matRssFeed,
    link   : 'https://twitter.quasar.dev',
  },
  {
    title  : 'Facebook',
    caption: '@QuasarFramework',
    icon   : matPublic,
    link   : 'https://facebook.quasar.dev',
  },
  {
    title  : 'Quasar Awesome',
    caption: 'Community Quasar projects',
    icon   : matFavorite,
    link   : 'https://awesome.quasar.dev',
  },
];

export default {
  name: 'MainLayout',

  components: {
    Error404,
    AppNavbar,
    AppFooter,
    EssentialLink,
  },

  data: () => ({
    left          : false,
    right         : false,
    essentialLinks: linksData,
  }),

  computed: {
    ...mapGetters(['showErrorPage']),
    lsEnabled() {
      return false;// this.$route.meta.left;
    },
    rsEnabled() {
      return false;//!!this.$route.matched[this.$route.matched.length - 1].components.right;
    },
  },
};
</script>

<template>
  <q-layout view="hHh LpR lff">
    <q-header reveal elevated class="bg-primary text-white">
      <AppNavbar
        :ls-left="lsEnabled"
        @toggle-left-drawer="left=!left"
      />
    </q-header>

    <q-drawer v-model="left" side="left" overlay bordered v-if="lsEnabled">
      <q-list>
        <q-item-label header class="text-grey-8"> Essential Links</q-item-label>
<!--
        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
-->
      </q-list>
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
