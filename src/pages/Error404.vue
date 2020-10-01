<!--
  WofhTools
  Page: Error404.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import ErrorBox from '@/components/ErrorBox';
export default {
  name: 'Error404',
  components: { ErrorBox },
};
</script>

<template>
  <div>
  <q-page padding class="flex items-center justify-center">
    <ErrorBox
      theme="info"
      title="Упс! Страница не найдена."
      code="404"
    >
      <p>Вы перешли на страницу, которой не существует, либо она была удалена.</p>
      <p>
        <router-link to="/">Перейти на главную</router-link>
      </p>
    </ErrorBox>
  </q-page>
  </div>
</template>
