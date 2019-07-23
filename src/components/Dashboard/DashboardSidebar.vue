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
    <aside class="main-sidebar dashboard-sidebar">

        <section class="sidebar">

            <ul class="sidebar-menu" data-widget="tree">
                <li class="header">WOFH</li>
                <li class="active"><router-link to="/dashboard/worlds">Worlds</router-link></li>
                <li class="header">DEMO COMPONENTS</li>
                <li class="active"><router-link to="/dashboard/type">Typography</router-link></li>
                <li class="active"><router-link to="/dashboard/palette">Color palette</router-link></li>
                <li class="active"><router-link to="/dashboard/boxes">Boxes</router-link></li>
            </ul>
        </section>
    </aside>
</template>
