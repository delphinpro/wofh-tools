<script>/*!
 * WofhTools
 * Component: UserMenu.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { AUTH_LOGOUT } from '@/store/actions/auth';


export default {
    name: 'UserMenu',

    data: () => ({
        username: 'Сергей delphinpro',
        usermenuOpen: false,
    }),

    methods: {
        logout() {
            this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
        },
    },
};
</script>

<template>
    <ul class="nav-menu">
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
        <li class="nav-menu__item">
            <router-link class="nav-menu__link" to="/login">
                <span class="nav-menu__icon fa fa-sign-in-alt"></span>
                <span>Sign in</span>
            </router-link>
        </li>
    </ul>
</template>
