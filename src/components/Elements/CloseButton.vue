<script>/*!
 * WofhTools
 * File: CloseButton.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/times';


export default {
    name: 'CloseButton',

    methods:{
        onClick(e){
            this.$emit('click', e);
        }
    }
};
</script>

<template>
    <button class="close-button" type="button" @click="onClick">
        <FaIcon class="close-button__icon" name="times"/>
    </button>
</template>

<style src="../@css/CloseButton.scss" lang="scss"></style>
