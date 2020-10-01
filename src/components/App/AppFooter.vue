<!--
  WofhTools
  Component: AppFooter.vue
  © 2019—2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { mapGetters } from 'vuex';

export default {
  name    : 'AppFooter',
  computed: {
    ...mapGetters(['projectUpdatedAt']),
    project() { return this.$store.state.common.project; },
  },
};
</script>
<template>
  <q-footer class="AppFooter">
    <div class="AppFooter__container q-px-md q-py-sm">
      <div>
        <p>
          © {{ project.name }}, v{{ project.version }},
          <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
          2013—<span v-if="projectUpdatedAt">{{ projectUpdatedAt.getFullYear() }}</span><span v-else>2020</span> гг.
        </p>
        <p v-if="projectUpdatedAt">
          <router-link to="/changelog">Last update: {{ projectUpdatedAt.toLocaleString() }}</router-link>
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
        <img src="@/assets/img/counter.png" alt="">
      </div>
    </div>
  </q-footer>
</template>

<style lang="scss">
.AppFooter {
  font-size: 11px;
  color: $wt-color-text;
  background: darken(darken($wt-bg, 3%), 3%);

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
    &:hover { color: lighten($wt-color-text, 10%); }
  }
}
</style>
