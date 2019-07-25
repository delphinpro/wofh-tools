<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
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
            <ol class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i> Главная</a></li>
                <li class="active">Here</li>
            </ol>
            <div class="page-header">
                <h1>Page heading <small>Optional description</small></h1>
            </div>
            <router-view/>
        </div>
    </div>
</template>
