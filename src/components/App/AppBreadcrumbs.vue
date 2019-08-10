<script>/*!
 * WofhTools
 * File: AppBreadcrumbs.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/home';
import AppBreadcrumb from '@/components/App/AppBreadcrumb';


export default {
    name: 'AppBreadcrumbs',

    components: {
        AppBreadcrumb,
    },
};
</script>

<template>
    <ol class="breadcrumb" v-if="$route.name !== 'home'">
        <li>
            <router-link :to="{ name: 'home' }">
                <FaIcon name="home"/>
                <span>WofhTools</span>
            </router-link>
        </li>
        <AppBreadcrumb
            v-for="(route, index) in $route.matched"
            :key="index"
            :route="route"
            :last="index===$route.matched.length-1"
        />
    </ol>
</template>

<style lang="scss" src="../@css/AppBreadcrumbs.scss"></style>
