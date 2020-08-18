<!--
  WofhTools
  View: Dashboard.vue
  © 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import DashboardSidebar from '@/components/Dashboard/DashboardSidebar';


export default {
  name: 'Dashboard',

  components: {
    DashboardSidebar,
  },
};
</script>

<template>
  <div class="dashboard-layout">
    <div class="dashboard-layout__sidebar">
      <DashboardSidebar/>
    </div>
    <div class="dashboard-layout__main">
      <PageHeader/>
      <router-view/>
    </div>
  </div>
</template>
