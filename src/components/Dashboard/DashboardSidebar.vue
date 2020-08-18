<!--
  WofhTools
  Component: DashboardSidebar.vue
  © 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
const includeCssDemo = (process.env.NODE_ENV === 'development') && (process.env.WEBPACK_TARGET !== 'node');

export default {
  name: 'DashboardSidebar',

  data: () => ({
    includeCssDemo,
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
      <template v-if="includeCssDemo">
        <li class="dashboard-menu-header">DEMO COMPONENTS</li>
        <li class="dashboard-menu-parent" :class="{'active open':demoUIElementsOpen}">
          <a href="#" @click.prevent="demoUIElementsOpen=!demoUIElementsOpen">
            <FaIcon name="laptop"/>
            <span>UI Elements</span>
            <FaIcon name="angle-left"/>
          </a>
          <ul>
            <li><router-link to="/dashboard/css/palette">Color palette</router-link></li>
            <li><router-link to="/dashboard/css/type">Typography</router-link></li>
            <li><router-link to="/dashboard/css/icons">Icons</router-link></li>
            <li><router-link to="/dashboard/css/buttons">Buttons</router-link></li>
            <li><router-link to="/dashboard/css/inputs">Inputs</router-link></li>
          </ul>
        </li>
        <li class="dashboard-menu-parent" :class="{'active open':demoWidgetsOpen}">
          <a href="#" @click.prevent="demoWidgetsOpen=!demoWidgetsOpen">
            <FaIcon name="th"/>
            <span>Widgets</span>
            <FaIcon name="angle-left"/>
          </a>
          <ul>
            <li><router-link to="/dashboard/css/boxes">Boxes</router-link></li>
            <li><router-link to="/dashboard/css/alerts">Alerts</router-link></li>
          </ul>
        </li>
      </template>
    </ul>
  </aside>
</template>
