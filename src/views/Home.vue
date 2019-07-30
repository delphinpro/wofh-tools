<script>/*!
 * WofhTools
 * View: Home.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import HelloWorld from '@/components/HelloWorld';
import DashboardSidebar from '@/components/Dashboard/DashboardSidebar';
import Login from '@/views/Login';

export default {
    name: 'Home',

    components: {
        HelloWorld,
        DashboardSidebar,
        Login,
    },
    computed: {},
};
</script>

<template>
    <div class="container">
        <PageHeader
            title="WofhTools"
            desc=""
            :crumbs="false"
        />
    </div>
</template>

<style>
</style>
