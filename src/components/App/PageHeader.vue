<script>/*!
 * WofhTools
 * PageHeader.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import AppBreadcrumbs from '@/components/App/AppBreadcrumbs';


export default {
    name: 'PageHeader',

    components: {
        AppBreadcrumbs,
    },

    props: {
        title: String,
        desc: String,
        crumbs: { type: Boolean, default: true },
    },
};
</script>

<template>
    <div>
        <AppBreadcrumbs v-if="crumbs"/>
        <div class="page-header" v-if="title">
            <h1><span v-html="title"></span><small v-text="desc" v-if="desc"></small></h1>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/PageHeader.scss"></style>
