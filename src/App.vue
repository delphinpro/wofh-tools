<!--
  WofhTools
  Component: App.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'App',

  preFetch({ store }) {
    return Promise.all([
      store.dispatch('updateProjectInfo'),
      store.dispatch('updateWorlds'),
    ]);
  },

  created() {
    this.$q.dark.set(true);
  },
};
</script>

<template>
  <div id="q-app">
    <router-view/>
  </div>
</template>
