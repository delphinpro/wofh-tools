<!--
  WofhTools
  File: AppBreadcrumbs.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import AppBreadcrumb from '@/components/App/AppBreadcrumb';


export default {
  name: 'AppBreadcrumbs',

  components: {
    AppBreadcrumb,
  },
};
</script>

<template>
  <ol class="breadcrumb" v-if="$route.name !== 'home'">
    <li>
      <router-link :to="{ name: 'home' }">
        <FaIcon name="home"/>
        <span>Главная</span>
      </router-link>
    </li>
    <AppBreadcrumb
      :key="index"
      :last="index===$route.matched.length-1"
      :route="route"
      v-for="(route, index) in $route.matched"
    />
  </ol>
</template>
