<script>/*!
 * WofhTools
 * PageHeader.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'PageHeader',

    props: {
        title: String,
        desc: String,
        crumbs: { type: Boolean, default: true },
    },
};
</script>

<template>
    <div>
        <ol class="breadcrumb" v-if="crumbs">
            <li><a href="/"><i class="fa fa-home"></i> Главная</a></li>
            <li class="active">Here</li>
        </ol>
        <div class="page-header" v-if="title">
            <h1><span v-html="title"></span><small v-text="desc" v-if="desc"></small></h1>
        </div>
    </div>
</template>
