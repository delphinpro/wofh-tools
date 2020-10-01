<!--
  WofhTools
  File: AppBreadcrumbs.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mdiHome } from '@quasar/extras/mdi-v5';

export default {
  name: 'AppBreadcrumbs',

  data: () => ({
    mdiHome,
  }),

  computed: {
    crumbs() {
      return this.$crumbs.generate(this.$route);
    },
  },
};
</script>

<template>
  <q-toolbar :inset="false"
    class="AppBreadcrumbs"
    style="min-height: 24px;"
    v-if="crumbs.length && $route.name !== 'home'"
  >
    <q-breadcrumbs>
      <q-breadcrumbs-el
        :key="index"
        :label="item.label"
        :icon="item.icon"
        :to="{name: item.name}"
        v-for="(item, index) in crumbs"
      />
    </q-breadcrumbs>
  </q-toolbar>
</template>

<style lang="scss">
.AppBreadcrumbs {
  font-size: 12px;
  background: $wt-bg-dark;
  background: $wt-bg-box;
  a {
    color: $wt-color-link !important;
    &:hover { color: $wt-color-link-hover !important; }
  }
  .q-breadcrumbs--last {
    a { color: #fff !important; }
  }
}
</style>
