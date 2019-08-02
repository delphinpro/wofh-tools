<script>/*!
 * WofhTools
 * ErrorBox.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/exclamation-triangle';


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
        iconName() {
            return this.icon;
        },
    },
};
</script>

<template>
    <div class="error-box" :class="rootClasses">
        <h2 class="error-box__error-code" v-if="code">{{code}}</h2>
        <div class="error-box__body">
            <div class="error-box__title">
                <FaIcon class="error-box__icon" :name="iconName" v-if="icon"/>
                {{title}}
            </div>
            <div class="error-box__content">
                <slot name="default"></slot>
            </div>
        </div>
    </div>
</template>

<style src="../@css/ErrorBox.scss" lang="scss"></style>
