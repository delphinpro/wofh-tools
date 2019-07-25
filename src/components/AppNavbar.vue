<script>/*!
 * WofhTools
 * Component: AppNavbar.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { mapGetters, mapState } from 'vuex';
// import { AUTH_LOGOUT } from '@/store/actions/auth';
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
            // { id: 3, title: 'Квесты',icon: null, path: '/quests' },
            // { id: 4, title: 'Таблицы', icon: null,path: 'tables' },
        ],
    }),

    computed: {
        ...mapGetters(['getProfile', 'isAuthenticated', 'isProfileLoaded']),
        ...mapState({
            // authLoading: state => state.auth.status === 'loading',
            name: state => `${state.user.profile.title} ${state.user.profile.name}`,
        }),
    },

};
</script>

<template>
    <header class="main-header">
        <div class="main-header__container">
            <AppLogo class="main-header__logo"/>
            <!--<button class="main-header__sidebar-toggle sidebar-toggle" type="button">
                <span class="fa fa-bars"></span>
                <span class="sr-only">Toggle navigation</span>
            </button>-->
            <NavMenu class="main-header__mainmenu" :items="mainmenu"/>
            <nav class="navbar navbar-static-top" hidden role="navigation">
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown messages-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                                                </div>
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock"></i> 5 mins</small>
                                                </h4>
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>

                        <li class="dropdown notifications-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-bell"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        <li class="dropdown tasks-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <i class="fa fa-flag"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua"
                                                        style="width: 20%"
                                                        role="progressbar"
                                                        aria-valuenow="20"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown user user-menu">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                                <!--<img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                                <span class="hidden-xs">Alexander Pierce</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="user-header">
                                    <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                                    <p>
                                        Alexander Pierce - Web Developer
                                        <small>Member since Nov. 2012</small>
                                    </p>
                                </li>
                                <li class="user-body">
                                    <div class="row">
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Followers</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Sales</a>
                                        </div>
                                        <div class="col-xs-4 text-center">
                                            <a href="#">Friends</a>
                                        </div>
                                    </div>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a class="btn btn-default btn-flat" href="#">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a class="btn btn-default btn-flat" href="#">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-cogs"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
            <UserMenu class="main-header__usermenu"/>

        </div>

        <!--<div class="navbar">
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
        </div>-->
    </header>
</template>
