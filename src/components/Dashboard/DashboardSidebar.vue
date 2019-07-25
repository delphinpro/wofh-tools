<script>/*!
 * WofhTools
 * Component: DashboardSidebar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'DashboardSidebar',

    data: () => ({
        demoUIElementsOpen: false,
        demoWidgetsOpen: false,
    }),
};
</script>

<template>
    <aside class="dashboard-sidebar">
        <ul class="dashboard-menu sidebar-menu">
            <li class="dashboard-menu-header">WOFH</li>
            <li><router-link to="/dashboard/worlds">Worlds</router-link></li>
            <li class="dashboard-menu-header">DEMO COMPONENTS</li>
            <li class="dashboard-menu-parent" :class="{'active open':demoUIElementsOpen}">
                <a href="#" @click.prevent="demoUIElementsOpen=!demoUIElementsOpen">
                    <i class="fa fa-laptop"></i>
                    <span>UI Elements</span>
                    <i class="fa fa-angle-left"></i>
                </a>
                <ul>
                    <li><router-link to="/dashboard/palette">Color palette</router-link></li>
                    <li><router-link to="/dashboard/type">Typography</router-link></li>
                    <li><router-link to="/dashboard/icons">Icons</router-link></li>
                    <li><router-link to="/dashboard/buttons">Buttons</router-link></li>
                    <li><router-link to="/dashboard/inputs">Inputs</router-link></li>
                </ul>
            </li>
            <li class="dashboard-menu-parent" :class="{'active open':demoWidgetsOpen}">
                <a href="#" @click.prevent="demoWidgetsOpen=!demoWidgetsOpen">
                    <i class="fa fa-th"></i>
                    <span>Widgets</span>
                    <i class="fa fa-angle-left"></i>
                </a>
                <ul>
                    <li><router-link to="/dashboard/boxes">Boxes</router-link></li>
                    <li><router-link to="/dashboard/alerts">Alerts</router-link></li>
                </ul>
            </li>
        </ul>
    </aside>
</template>
