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
    <router-link class="app-logo" to="/">
        <img class="app-logo__image" src="../../assets/main/logo-48.png" alt="" width="48" height="48">
        <span class="app-logo__name">{{$store.getters.projectName}}</span>
        <span class="app-logo__beta">beta</span>
    </router-link>
</template>

<style lang="scss" src="../@css/AppLogo.scss"></style>
