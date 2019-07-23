<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { mapGetters, mapState } from 'vuex';
import { AUTH_LOGOUT } from '@/store/actions/auth';
import AppLogo from './AppLogo';


export default {
    components: {
        AppLogo,
    },

    data: () => ({}),

    computed: {
        ...mapGetters(['getProfile', 'isAuthenticated', 'isProfileLoaded']),
        ...mapState({
            authLoading: state => state.auth.status === 'loading',
            name: state => `${state.user.profile.title} ${state.user.profile.name}`,
        }),
    },

    methods: {
        logout: function () {
            this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
        },
    },
};
</script>

<template>
        </div>
</template>

<style lang="scss">
    .main-header {
        @include font(13px);
        position: fixed;
        background: $main-header-background;
        color: $main-header-color;
        box-shadow: $shadow-bar;
        z-index: $z-navbar-fixed;

        &__container {
            height: rhythm($navbar-height-rhythm);
            display: flex;
        }

        &__logo {
            width: $sidebar-width;
            flex-shrink: 0;
            background: $wt-second-color-dark;
        }

        &__mainmenu {
            margin-right: 1em;
        }

        &__usermenu {
            margin-left: auto;
        }
    }

    .sidebar-toggle {
        @include size(rhythm($navbar-height-rhythm));
        @include wt-transition(background-color);
        border: none;
        background: none;
        color: currentColor;
        padding: 0;

        &:hover {
            color: $main-header-color-hover;
            background-color: $main-header-background-hover;
        }

        &:focus,
        &:active {
            background: transparent;
        }
    }
</style>
