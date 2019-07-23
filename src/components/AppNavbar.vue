<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import { mapGetters, mapState } from 'vuex';
import { AUTH_LOGOUT } from '@/store/actions/auth';
import AppLogo from './AppLogo';
import TopMenu from './TopMenu';


export default {
    components: {
        AppLogo,
        TopMenu,
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
    <div class="navbar">
        <div class="navbar__container container">
            <AppLogo class="navbar__logo"/>
            <TopMenu class="navbar__mainmenu"/>
            <ul class="navbar__usermenu top-menu">
                <li class="top-menu__item">
                    <router-link to="/dashboard" class="top-menu__link">Dashboard</router-link>
                </li>
                <li v-if="isProfileLoaded" class="top-menu__item">
                    <router-link to="/account" class="top-menu__link">{{name}}</router-link>
                </li>
                <li v-if="isAuthenticated" @click="logout" class="top-menu__item">
                    <span class="logout top-menu__link">Logout</span>
                </li>
                <li v-if="!isAuthenticated && !authLoading" class="top-menu__item">
                    <router-link to="/login" class="top-menu__link">Login</router-link>
                </li>
            </ul>
        </div>
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
