<script>/*!
 * WofhTools
 * Component: UserMenu.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { AUTH_LOGOUT } from '@/store/actions/auth';
import { mapGetters, mapState } from 'vuex';


const includeCssDemo = (process.env.NODE_ENV === 'development') && (process.env.WEBPACK_TARGET !== 'node');

export default {
    name: 'UserMenu',

    data: () => ({
        includeCssDemo,
        username: 'Сергей delphinpro',
        usermenuOpen: false,
    }),

    computed: {
        ...mapGetters([
            'getProfile',
            'isAuth',
            'isProfileLoaded',
        ]),
        ...mapState({
            name: state => `${state.user.profile.title} ${state.user.profile.name}`,
        }),
    },

    methods: {
        logout() {
            this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
        },
    },
};
</script>

<template>
    <ul class="nav-menu">
        <template v-if="isAuth">
            <li class="nav-menu__item">
                <router-link class="nav-menu__link" to="/dashboard">
                    <span class="nav-menu__icon fa fa-cogs"></span>
                    <span>Dashboard</span>
                </router-link>
            </li>
            <li class="nav-menu__item" @mouseleave="usermenuOpen=false">
                <span class="nav-menu__link" @click.prevent="usermenuOpen=!usermenuOpen">
                    <span class="nav-menu__icon fa fa-user"></span>
                    <span>{{username}}</span>
                </span>
                <ul class="usermenu" v-if="usermenuOpen" @click="usermenuOpen=false">
                    <li class="usermenu__item" v-if="includeCssDemo">
                        <router-link class="usermenu__link" to="/dashboard/css/type">
                            <span class="usermenu__icon"></span>
                            <span>CSS Elements</span>
                        </router-link>
                    </li>
                    <li class="usermenu__item">
                        <router-link class="usermenu__link" to="/profile">
                            <span class="usermenu__icon"></span>
                            <span>Profile</span>
                        </router-link>
                    </li>
                    <li class="usermenu__item">
                    <span class="usermenu__link" @click="logout">
                        <span class="usermenu__icon fa fa-sign-out-alt"></span>
                        <span>Sign out</span>
                    </span>
                    </li>
                </ul>
            </li>
        </template>
        <template v-if="!isAuth">
            <li class="nav-menu__item">
                <router-link class="nav-menu__link" to="/registration">
                    <span class="nav-menu__icon fa fa-user-plus"></span>
                    <span>Sign up</span>
                </router-link>
            </li>
            <li class="nav-menu__item">
                <router-link class="nav-menu__link" to="/login">
                    <span class="nav-menu__icon fa fa-sign-in-alt"></span>
                    <span>Sign in</span>
                </router-link>
            </li>
        </template>
    </ul>
</template>
