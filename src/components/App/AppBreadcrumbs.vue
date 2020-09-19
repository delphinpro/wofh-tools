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

  methods: {
    bcValue(route) { return route.meta.crumbsGetter ? this.$store.getters[route.meta.crumbsGetter] : null; },
    formattedValue(route) { return route.meta.crumbsText(this.value); },
    loadingText(route) { return route.meta.crumbsLoadingText || '…?'; },
    routerLinkText(route) {
      let text = '';
      if (!route.meta.crumbsGetter) {
        text += route.meta.crumbsText;
      } else {
        text += this.bcValue(route) ? this.formattedValue(route) : this.loadingText(route);
      }
      return text;
    },
    routerLinkTo(route) {
      if (this.last) return null;
      let to = { name: route.name };
      if (route.meta.crumbsGetter) to.params = { id: this.$route.params.id };
      return to;
    },
  },
};
</script>

<template>
  <QToolbar :inset="false" class="app-breadcrumbs" style="min-height: 24px;">
    <QBreadcrumbs>
      <QBreadcrumbsEl label="Главная" :icon="mdiHome" :to="{ name: 'home' }"/>
      <QBreadcrumbsEl
        :key="index"
        :label="routerLinkText(route)"
        :to="routerLinkTo(route)"
        v-for="(route, index) in $route.matched"
        v-if="route.meta.crumbsText"
      />
    </QBreadcrumbs>
  </QToolbar>
</template>

<style lang="scss">
@import "src/_sass/config/cfg-app";

.app-breadcrumbs {
  font-size: 12px;
  background: $background-base-dark;
  a {
    color: $color-link !important;
    &:hover { color: $color-link-hover !important; }
  }
  .q-breadcrumbs--last {
    a {
      color: #fff !important;
    }
  }
}
</style>
