<!--
  WofhTools
  Component: AppLogo.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'AppLogo',

  computed: {
    projectName() {
      return this.$store.state.common.project.name;
    },
  },
};
</script>

<template>
  <router-link class="AppLogo" to="/">
    <img class="AppLogo__image" src="@/assets/img/logo-96x96.png" alt="" width="48" height="48">
    <span class="AppLogo__name">{{ projectName }}</span>
    <span class="AppLogo__beta">beta</span>
  </router-link>
</template>

<style lang="scss">
.AppLogo {
  @extend %font-family-head;
  display: flex;
  align-items: center;
  text-decoration: none !important;
  color: currentColor !important;
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
