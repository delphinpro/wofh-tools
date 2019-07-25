<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { mapGetters, mapState } from 'vuex';
import AppLogo from './AppLogo';
import NavMenu from './NavMenu';
import UserMenu from './UserMenu';


export default {
    components: {
        AppLogo,
        NavMenu,
        UserMenu,
    },

    data: () => ({
        mainmenu: [
            { id: 1, title: 'Статистика', icon: null, path: '/stat' },
            { id: 2, title: 'Науки', icon: null, path: '/technology' },
            { id: 3, title: 'Квесты', icon: null, path: '/quests' },
            { id: 4, title: 'Таблицы', icon: null, path: 'tables' },
        ],
    }),

    computed: {
        ...mapGetters(['getProfile', 'isAuthenticated', 'isProfileLoaded']),
        ...mapState({
            name: state => `${state.user.profile.title} ${state.user.profile.name}`,
        }),
    },

};
</script>

<template>
    <header class="app-navbar">
        <div class="app-navbar__container">
            <AppLogo class="app-navbar__logo"/>
            <!--<button class="app-navbar__sidebar-toggle sidebar-toggle" type="button">
                <span class="fa fa-bars"></span>
                <span class="sr-only">Toggle navigation</span>
            </button>-->
            <NavMenu class="app-navbar__mainmenu" :items="mainmenu"/>
            <UserMenu class="app-navbar__usermenu"/>
        </div>
    </header>
</template>
