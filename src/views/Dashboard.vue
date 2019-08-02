<script>/*!
 * WofhTools
 * View: Dashboard.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

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
            <PageHeader
                title="Dashboard"
                desc=""
                :crumbs="true"
            />
            <router-view/>
        </div>
    </div>
</template>
