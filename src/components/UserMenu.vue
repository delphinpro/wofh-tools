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

<style lang="scss">/*
    .usermenu {
        display: block;
        position: absolute;
        top: 100%;
        left: auto;
        right: 0;
        z-index: $z-dropdown;
        min-width: 100%;
        padding: 0;
        margin: 0;
        font-size: $font-size-base;
        text-align: left;
        list-style: none;
        background-color: $palette-light-blue;
        border: 1px solid darken($palette-light-blue, 5%);
        border-radius: 0;
        box-shadow: $shadow-bar;

        &__item {
            position: relative;

            &:not(:first-child) {
                border-top: 1px solid rgba(#000, 0.2);
            }
        }

        &__link {
            display: flex;
            align-items: center;
            color: currentColor;
            padding: rhythm(0.25) rhythm(0.5) rhythm(0.25) rhythm(1.5);
            cursor: pointer;

            &:hover {
                color: $main-header-color-hover;
                background-color: $main-header-background-hover;
            }
        }

        &__icon {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            padding: rhythm(0.25) rhythm(0.5);
        }
    }
    */
</style>
