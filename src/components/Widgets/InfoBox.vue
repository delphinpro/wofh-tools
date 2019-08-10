<script>/*!
 * WofhTools
 * File: InfoBox.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'InfoBox',

    props: {
        link: { type: [String, Object], default: null },
        theme: { type: String, default: null },
    },

    computed: {
        tag() { return this.link ? 'router-link' : 'div'; },
    },
};
</script>

<template>
    <component class="info-box" :class="theme ? 'info-box_theme_'+theme : ''" :is="tag" :to="link">
        <div class="info-box__icon">
            <slot name="info"></slot>
        </div>
        <div class="info-box__body">
            <div class="info-box__content">
                <slot name="default"></slot>
            </div>
            <div class="info-box__footer">
                <slot name="footer"></slot>
            </div>
        </div>
    </component>
</template>

<style lang="scss" src="../@css/InfoBox.scss"></style>
