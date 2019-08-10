<script>/*!
 * WofhTools
 * File: AppBreadcrumb.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'AppBreadcrumb',
    props: {
        route: { type: Object },
        last: { type: Boolean, default: false },
    },
    beforeCreate() {
        this.$options.computed.value = function () {
            return this.route.meta.crumbsGetter ? this.$store.getters[this.route.meta.crumbsGetter] : null;
        };
    },
    computed: {
        formattedValue() { return this.route.meta.crumbsText(this.value); },
        loadingText() { return this.route.meta.crumbsLoadingText || '…?'; },
        routerLinkTo() {
            if (this.last) return null;
            let to = { name: this.route.name };
            if (this.route.meta.crumbsGetter) to.params = { id: this.$route.params.id };
            return to;
        },
        routerLinkText() {
            let text = '';
            if (!this.route.meta.crumbsGetter) {
                text += this.route.meta.crumbsText;
            } else {
                text += this.value ? this.formattedValue : this.loadingText;
            }
            return text;
        },
    },
};
</script>

<template>
    <li v-if="route.meta.crumbsText">
        <component :to="routerLinkTo" :is="last ? 'span': 'router-link'">
            {{routerLinkText}}
        </component>
    </li>
</template>
