<script>/*!
 * WofhTools
 * File: AppBreadcrumbs.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/home';

export default {
    name: 'AppBreadcrumbs',
};
</script>

<template>
    <ol class="breadcrumb">
        <li><a href="/"><FaIcon name="home"/><span>Главная</span></a></li>
        <li class="active">Here</li>
    </ol>
</template>

<style lang="scss" src="../@css/AppBreadcrumbs.scss"></style>
