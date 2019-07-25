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
