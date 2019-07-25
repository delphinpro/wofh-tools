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
    <aside class="main-sidebar dashboard-sidebar">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <li class="header">WOFH</li>
                <li class=""><router-link to="/dashboard/worlds">Worlds</router-link></li>
                <li class="header">DEMO COMPONENTS</li>
                <li class="treeview" :class="{'active menu-open':demoUIElementsOpen}">
                    <a href="#" @click.prevent="demoUIElementsOpen=!demoUIElementsOpen">
                        <i class="fa fa-laptop"></i> <span>UI Elements</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class=""><router-link to="/dashboard/palette">Color palette</router-link></li>
                        <li class=""><router-link to="/dashboard/type">Typography</router-link></li>
                        <li class=""><router-link to="/dashboard/icons">Icons</router-link></li>
                        <li class=""><router-link to="/dashboard/buttons">Buttons</router-link></li>
                        <li class=""><router-link to="/dashboard/inputs">Inputs</router-link></li>
                    </ul>
                </li>
                <li class="treeview" :class="{'active menu-open':demoWidgetsOpen}">
                    <a href="#" @click.prevent="demoWidgetsOpen=!demoWidgetsOpen">
                        <i class="fa fa-th"></i> <span>Widgets</span>
                        <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                    </a>
                    <ul class="treeview-menu">
                        <li class=""><router-link to="/dashboard/boxes">Boxes</router-link></li>
                        <li class=""><router-link to="/dashboard/alerts">Alerts</router-link></li>
                    </ul>
                </li>
            </ul>
        </section>
    </aside>
</template>
