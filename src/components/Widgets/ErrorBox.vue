<!--
  WofhTools
  ErrorBox.vue
  (c) 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mdiAlert } from '@quasar/extras/mdi-v5';

export default {
  name: 'ErrorBox',

  props: {
    theme   : String,
    title   : String,
    code    : [String, Number],
    icon    : { default: mdiAlert },
    lighting: { type: Boolean, default: false },
  },

  computed: {
    rootClasses() {
      let classes = '';
      if (this.theme) classes += `ErrorBox_theme_${this.theme}`;
      if (this.lighting) classes += ` ErrorBox_lighting`;
      return classes;
    },
    iconName() {
      return this.icon;
    },
  },
};
</script>

<template>
  <div class="ErrorBox" :class="rootClasses">
    <div class="ErrorBox__error-code" v-if="code">{{ code }}</div>
    <div class="ErrorBox__body">
      <div class="ErrorBox__title">
        <q-icon class="ErrorBox__icon" :name="iconName" v-if="icon"/>
        {{ title }}
      </div>
      <div class="ErrorBox__content">
        <slot name="default"></slot>
      </div>
    </div>
  </div>
</template>

<style lang="scss">
$ErrorBox-title-height: 1.25;

:root {
  --theme-color: #{$color-text-base};
}

.ErrorBox {
  display: flex;
  align-items: center;
  width: 650px;
  max-width: 100%;
  margin: 0;
  padding: ($space-base * 1.5) ($space-base * 2) ($space-base * 2);
  background: darken($background-base, 1%);
  box-shadow: $shadow-2;

  &__error-code {
    font-size: 100px;
    line-height: 1em;
    margin-bottom: 0;
    transform: scaleY(1.6) scaleX(0.95);
    transform-origin: 0 50%;
    color: var(--theme-color);
    font-family: $wt-family-base;
    font-weight: 400;
  }

  &__error-code + &__body {
    padding-left: $space-base;
  }

  &_lighting &__error-code {
    text-shadow: 0 0 20px var(--theme-color);
  }

  &__title {
    font-size: 24px;
    font-family: $wt-family-head;
    display: flex;
    align-items: baseline;
  }

  &__icon {
    @include size(1em);
    margin-right: .25em;
    color: var(--theme-color);
    flex-shrink: 0;
    position: relative;
    bottom: -0.15em;
  }

  &__content {
    padding-top: $space-base;

    p {
      margin: 0 0 ($space-base / 4);
      &:last-child { margin-bottom: 0; }
    }

    a {
      text-decoration: underline;
      opacity: 0.8;
      color: var(--theme-color);

      &:hover {
        opacity: 1;
        color: $color-link-hover;
      }
    }
  }

  @media (max-width: $breakpoint-xs) {
    display: block;
    background: none;
    box-shadow: none;
    &__title {
      font-size: 18px;
      margin-top: $space-base;
    }
    &__error-code + &__body {
      padding-left: 0;
    }
    &__error-code {
      font-size: 50px;
    }
  }

  &_theme_primary { --theme-color: #{$color-primary}; }
  &_theme_info { --theme-color: #{$color-info}; }
  &_theme_success { --theme-color: #{$color-success}; }
  &_theme_warning { --theme-color: #{$color-warning}; }
  &_theme_danger { --theme-color: #{$color-danger}; }
}
</style>
