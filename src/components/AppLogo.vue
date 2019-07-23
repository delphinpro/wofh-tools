<script>/*!
 * WofhTools
 * Component: AppLogo.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
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
        @include wt-transition(background-color);
        font-family: $wt-family-head;
        font-weight: 400;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none !important;
        color: currentColor !important;

        &__image {
            @include size(rhythm($navbar-height-rhythm - $navbar-padding-rhythm * 2));
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

        &:hover {
            background-color: $main-header-background-hover;
        }
    }
</style>
