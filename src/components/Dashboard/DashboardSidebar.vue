<script>/*!
 * WofhTools
 * Component: DashboardSidebar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'DashboardSidebar',

    data: () => ({
    }),
};
</script>

<template>
    <aside class="main-sidebar dashboard-sidebar">

        <section class="sidebar">

            <ul class="sidebar-menu">
                <li class="header">WOFH</li>
                <li class=""><router-link to="/dashboard/worlds">Worlds</router-link></li>
                <li class="header">DEMO COMPONENTS</li>
                <li class=""><router-link to="/dashboard/type">Typography</router-link></li>
                <li class=""><router-link to="/dashboard/palette">Color palette</router-link></li>
                <li class=""><router-link to="/dashboard/boxes">Boxes</router-link></li>
                <li class=""><router-link to="/dashboard/alerts">Alerts</router-link></li>
            </ul>
        </section>
    </aside>
</template>
