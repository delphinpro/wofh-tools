<script>/*!
 * WofhTools
 * Component: NavMenu.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'NavMenu',

    props: {
        items: { type: Array, default: [] },
    },

    data: () => ({}),
};
</script>

<template>
    <ul class="nav-menu" v-if="items.length">
        <li class="nav-menu__item" v-for="item in items" :key="item.id">
            <router-link class="nav-menu__link" :to="item.path">
                <span class="nav-menu__icon" :class="item.icon" v-if="item.icon"></span>
                <span>{{item.title}}</span>
            </router-link>
        </li>
    </ul>
</template>

<style lang="scss">
    .nav-menu {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        color: $main-header-color;

        &__item {
            display: flex;
            position: relative;
        }

        &__link {
            @include wt-transition(color, background-color);
            display: flex;
            align-items: center;
            padding: 0 1em;
            color: $main-header-color;
            text-decoration: none;
            cursor: pointer;
            user-select: none;
        }

        &__item:hover &__link {
            color: $main-header-color-hover;
            background-color: $main-header-background-hover;
        }

        &__icon {
            margin-right: 0.5em;
        }
    }
</style>
