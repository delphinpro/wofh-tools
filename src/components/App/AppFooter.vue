<!--
  WofhTools
  Component: AppFooter.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name    : 'AppFooter',
  computed: {
    appUpdatedAt() {
      if (window.WT && window.WT.updatedAt && typeof window.WT.updatedAt === 'number') {
        return new Date(window.WT.updatedAt * 1000);
      }
      return false;
    },
  },
};
</script>
<template>
  <q-footer class="AppFooter">
    <div class="AppFooter__container q-px-md q-py-sm">
      <div>
        <p>
          © {{ this.$store.getters.projectName }}, v{{ this.$store.getters.projectVer }},
          <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
          2013—{{ appUpdatedAt.getFullYear() }} гг.
        </p>
        <p v-if="appUpdatedAt">
          <router-link to="/changelog">Last update: {{ appUpdatedAt.toLocaleString() }}</router-link>
        </p>
      </div>
<!--
      <q-separator class="q-my-sm lt-md"/>
      <div class="flex justify-center">
        <q-btn size="sm" flat no-caps label="Обратная связь" to="/feedback"/>
        <q-btn size="sm" flat no-caps label="Карта сайта" to="sitemap"/>
      </div>
-->
      <q-separator class="q-my-sm lt-md"/>
      <div class="flex justify-center">
        <img src="/assets/images/main/counter.png" alt="">
      </div>
    </div>
  </q-footer>
</template>

<style lang="scss">
.AppFooter {
  font-size: 11px;
  color: $color-text-base;
  background: darken(darken($background-base, 3%), 3%);

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
