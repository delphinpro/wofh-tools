<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import { USER_REQUEST } from '@/store/actions/user';
import AppNavbar from '@/components/AppNavbar';
import AppFooter from '@/components/AppFooter';


export default {
    components: {
        AppNavbar,
        AppFooter,
    },

    data: () => ({}),

    created: function () {
        if (this.$store.getters.isAuthenticated) {
            this.$store.dispatch(USER_REQUEST);
        }
    },
};
</script>

<template>
    <div class="app">
        <AppNavbar class="app__navbar"/>
        <router-view class="app__main"/>
        <AppFooter class="app__footer"/>
    </div>
</template>

<style lang="scss">
    .app {
        min-height: 100vh;
        display: flex;
        flex-direction: column;

        &__navbar,
        &__footer {
            width: 100%;
        }

        &__navbar {
        }

        &__main {
            @include padding-trailer(2);
            flex-grow: 1;
        }

        &__footer {
        }
    }
</style>
