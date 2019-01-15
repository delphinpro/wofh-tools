<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

export default {
    data: () => ({}),
};
</script>

<template>
    <router-link class="logo" to="/">
        <img class="logo__image" src="../assets/main/logo-48.png" alt=""/>
        <span class="logo__name">{{$store.getters.projectName}}</span>
        <span class="logo__beta">beta</span>
    </router-link>
</template>

<style lang="scss">
    .logo {
        font-family: $family-head;
        font-weight: 400;
        display: flex;
        align-items: center;
        text-decoration: none !important;
        color: lighten($main-color, 30%) !important;

        &__image {
            @include size(rem($navbar-height - $navbar-padding * 2));
        }

        &__name {
            @include font(20px);
            transform: scaleY(1.3);
            margin-left: 0.5em;
        }

        &__beta {
            @include font(11px);
            position: relative;
            top: -0.5em;
            margin-left: 0.5em;
        }
    }
</style>
