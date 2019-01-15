<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import AppFooter from '@/components/AppFooter';

export default {
    components: {
        AppFooter,
    },

    data: () => ({}),
};
</script>

<template>
    <div id="app" class="app">
        <div class="app__navbar"></div>
        <div class="app__main"></div>
        <AppFooter class="app__footer"/>
    </div>
</template>

<style lang="scss">
    .app {
        min-height: 100vh;
        display: flex;
        flex-direction: column;

        &__navbar,
        &__main,
        &__footer {
            width: 100%;
        }

        &__navbar {
        }

        &__main {
            flex-grow: 1;
        }

        &__footer {
        }
    }
</style>
