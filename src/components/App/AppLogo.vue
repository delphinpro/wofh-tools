<!--
  WofhTools
  Component: AppLogo.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<template>
  <router-link class="app-logo" to="/">
    <img class="app-logo__image" src="/assets/images/main/logo-48.png" alt="" width="48" height="48">
    <span class="app-logo__name">{{ $store.getters.projectName }}</span>
    <span class="app-logo__beta">beta</span>
  </router-link>
</template>

<style lang="scss">
@import "src/app-styles/config";

.app-logo {
  display: flex;
  align-items: center;
  text-decoration: none !important;
  color: currentColor !important;
  font-family: $wt-family-head;
  font-weight: 400;
  padding-right: 1.5em;

  &__image {
    @include size(48px);
  }

  &__name {
    font-size: 20px;
    margin-left: 0.5em;
    transform: scaleY(1.3);
    white-space: nowrap;
  }

  &__beta {
    font-size: 11px;
    position: relative;
    top: -0.5em;
    margin-left: 0.5em;
  }

  @media (max-width: $breakpoint-xs) {
    &__name,
    &__beta {
      //display: none;
    }
  }
}
</style>
