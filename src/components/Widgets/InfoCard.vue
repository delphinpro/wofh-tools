<!--
  WofhTools
  File: InfoCard.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { farClock } from '@quasar/extras/fontawesome-v5';

export default {
  name: 'InfoCard',

  props: {
    icon    : String,
    title   : String,
    text    : String,
    skeleton: Boolean,
  },

  data: () => ({
    farClock,
    animation: 'wave',
  }),
};
</script>

<template>
  <div class="InfoCard">
    <q-skeleton class="InfoCard__icon" type="circle" :animation="animation" v-if="skeleton"/>
    <q-icon class="InfoCard__icon" :name="icon" v-else/>

    <div class="InfoCard__title">
      <q-skeleton type="text" :animation="animation" v-if="skeleton"/>
      <span v-else>{{ title }}</span>
    </div>
    <div class="InfoCard__content">
      <q-skeleton type="text" :animation="animation" v-if="skeleton"/>
      <span v-else>{{ text }}</span>
    </div>
  </div>
</template>

<style lang="scss">
@import "src/app-styles/config";

.InfoCard {
  $iconSize: $base-line-height * (2.5);

  position: relative;
  flex-grow: 1;
  padding: $space-base;
  background: $background-base-dark;
  min-width: ($iconSize * 2) + $space-base * 2;

  &__title {
    font-size: 30px;
    position: relative;
    font-family: $wt-family-head;
    font-weight: bold;
    width: 35%;
  }

  &__content {
    font-size: 14px;
    position: relative;
  }

  &__title + &__content {
    margin-top: map-get($space-sm, x);
  }

  &__icon {
    @include size($iconSize);
    position: absolute;
    top: $space-base;
    right: $space-base;
    color: rgba(#000, 0.2);
  }
}
</style>
