<!--
  WofhTools
  Component: AppFooter.vue
  © 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<template>
  <div class="app-footer">
    <div class="app-footer__container container-fluid">
      <div class="app-footer__section">
        <p>
          © {{ this.$store.getters.projectName }}, v{{ this.$store.getters.projectVer }},
          <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
          2013—2020 гг.
        </p>
        <p>
          <a href="#">Last update: xx.xx.xxxx, xx:xx</a>
        </p>
      </div>
      <div class="app-footer__section">
        <img src="/assets/images/main/counter.png" alt="">
      </div>
      <div class="app-footer__section text-md-right">
        <ul>
          <li><a href="#">Обратная связь</a></li>
          <li><a href="#">Карта сайта</a></li>
        </ul>
      </div>
    </div>
  </div>
</template>
