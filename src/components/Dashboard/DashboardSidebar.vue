<script>/*!
 * WofhTools
 * Component: DashboardSidebar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'DashboardSidebar',
};
</script>

<template>
    <aside class="dashboard-sidebar">
        <ul class="dashboard-menu sidebar-menu">
            <li class="dashboard-menu-header">WOFH</li>
            <li><router-link to="/dashboard/worlds">Worlds</router-link></li>
        </ul>
    </aside>
</template>
