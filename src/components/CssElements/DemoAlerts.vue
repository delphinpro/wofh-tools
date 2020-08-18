<!--
  WofhTools
  DemoAlerts.vue
  (c) 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
const content = 'A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.';

export default {
  name: 'DemoAlerts',

  data: () => ({
    content,
    alerts: [
      { title: 'Default', content, icon: null },
      { title: 'Info', content, icon: 'info' },
      { title: 'Success', content, icon: 'check' },
      { title: 'Warning', content, icon: 'exclamation-triangle' },
      { title: 'Danger', content, icon: 'ban' },
    ],
  }),
};
</script>

<template>
  <div class="mb-2">
    <div class="row">
      <div class="col-md-6">
        <h3 class="type-title">Alerts</h3>
        <Alert
          v-for="item in alerts" :key="item.title"
          :type="item.title.toLowerCase()"
          :title="item.title + ' alert!'"
          :icon="item.icon"
          :dismiss="true"
        >{{ item.content }}
        </Alert>
        <Alert
          title="Alert without content"
          :dismiss="true"
        />
      </div>
      <div class="col-md-6">
        <h3 class="type-title">Callouts</h3>
        <Alert
          v-for="item in alerts" :key="item.title"
          :type="item.title.toLowerCase()"
          :title="`I am a ${item.title.toLowerCase()} callout!`"
          :callout="true"
        >{{ item.content }}
        </Alert>
        <Alert
          title="Callout without content"
          :callout="true"
        />
      </div>
    </div>
  </div>
</template>
