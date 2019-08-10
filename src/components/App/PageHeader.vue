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
        title: { type: String, default: '' },
        subtitle: { type: String, default: '' },
        crumbs: { type: Boolean, default: true },
    },

    data: function () {
        return {
            pageTitle: this.title,
            pageSubtitle: this.subtitle,
        };
    },


    created() {
        this.updateTitles();
    },

    watch: {
        $route() { this.updateTitles(); },
        title() { this.updateTitles(); },
        subtitle() { this.updateTitles(); },
    },

    methods: {
        updateTitles: function () {
            this.pageTitle = this.$router.currentRoute.meta.pageTitle || this.title;
            this.pageSubtitle = this.$router.currentRoute.meta.pageSubtitle || this.subtitle;
        },
    },
};
</script>

<template>
    <div class="page-header-container">
        <AppBreadcrumbs v-if="crumbs"/>
        <div class="page-header" v-if="pageTitle">
            <h1><span v-html="pageTitle"></span><small v-html="pageSubtitle" v-if="pageSubtitle"></small></h1>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/PageHeader.scss"></style>
