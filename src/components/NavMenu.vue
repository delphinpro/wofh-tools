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
