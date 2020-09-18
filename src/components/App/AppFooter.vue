<!--
  WofhTools
  Component: AppFooter.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<template>
  <QFooter class="app-footer">
    <div class="app-footer__container q-px-md q-py-sm">
      <div>
        <p>
          © {{ this.$store.getters.projectName }}, v{{ this.$store.getters.projectVer }},
          <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
          2013—2020 гг.
        </p>
        <p>
          <a href="#">Last update: xx.xx.xxxx, xx:xx</a>
        </p>
      </div>
      <QSeparator class="q-my-sm lt-md"/>
      <div class="flex justify-center">
        <QBtn size="sm" flat no-caps label="Обратная связь"/>
        <QBtn size="sm" flat no-caps label="Карта сайта"/>
      </div>
      <QSeparator class="q-my-sm lt-md"/>
      <div class="flex justify-center">
        <img src="/assets/images/main/counter.png" alt="">
      </div>
    </div>
  </QFooter>
</template>

<style lang="scss">

@import "src/_sass/config/cfg-vars";

.app-footer {
  font-size: 11px;
  color: $color-text-base;
  background: darken($dashboard-sidebar-bg, 3%);

  &__container {
    text-align: center;
    @media (min-width: $breakpoint-md-min) {
      text-align: left;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
  }

  p { margin: 0; }

  a {
    color: inherit;
    &:hover { color: lighten($color-text-base, 10%); }
  }
}
</style>
