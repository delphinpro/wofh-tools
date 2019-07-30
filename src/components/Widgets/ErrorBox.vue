<script>/*!
 * WofhTools
 * ErrorBox.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'ErrorBox',

    props: {
        theme: String,
        title: String,
        code: [String, Number],
        icon: { type: String, default: 'exclamation-triangle' },
        lighting: { type: Boolean, default: false },
    },

    computed: {
        rootClasses() {
            let classes = '';
            if (this.theme) classes += `error-box_theme_${this.theme}`;
            if (this.lighting) classes += ` error-box_lighting`;
            return classes;
        },
        iconClass() {
            return 'fa-' + this.icon;
        },
    },
};
</script>

<template>
    <div class="error-box" :class="rootClasses">
        <h2 class="error-box__error-code" v-if="code">{{code}}</h2>
        <div class="error-box__body">
            <div class="error-box__title">
                <i class="error-box__icon fa" :class="iconClass"></i>
                {{title}}
            </div>
            <div class="error-box__content">
                <slot name="default"></slot>
            </div>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/ErrorBox.scss"></style>
