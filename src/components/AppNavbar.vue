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
    .navbar {
        background-color: $second-color;
        box-shadow: $shadow-bar;

        &__container {
            height: rem($navbar-height);
            display: flex;
        }

        &__logo {}

        &__mainmenu {
            margin-left: 1rem;
        }

        &__usermenu {
            margin-left: auto;
        }
    }
</style>
