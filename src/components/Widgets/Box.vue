<script>/*!
 * WofhTools
 * Component: Box.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'Box',

    props: {
        type: { type: String, default: '' },
        title: { type: String },
        icon: { type: String, default: '' },
    },
};
</script>

<template>
    <div class="box2" :class="(type ? 'box2_style_'+type : '')">
        <div class="box2__header" v-if="title">
            <FaIcon
                class="box2__icon"
                :name="icon"
                v-if="icon"
            ></FaIcon>
            <span class="box2__title">{{title}}</span>
        </div>
        <div class="box2__body">
            <slot></slot>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/Box.scss"></style>
