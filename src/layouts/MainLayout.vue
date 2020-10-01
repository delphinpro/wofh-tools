<!--
  WofhTools
  Component: MainLayout.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import EssentialLink from '@/components/EssentialLink.vue';
import AppFooter from '@/components/App/AppFooter';
import AppNavbar from '@/components/App/AppNavbar';
import { mdiChat, mdiSchool } from '@quasar/extras/mdi-v5';
import { fasCode } from '@quasar/extras/fontawesome-v5';
import {
  matChat,
  matCode,
  matFavorite,
  matPublic,
  matRecordVoiceOver,
  matRssFeed,
  matSchool,
} from '@quasar/extras/material-icons';

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
  <q-layout view="hHh LpR lff" id="app">
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
      <router-view/>
    </q-page-container>

    <AppFooter/>

  </q-layout>
</template>
