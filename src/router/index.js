/*!
 * WofhTools
 * File: router/index.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Router from 'vue-router';
import HomeView from '@/views/HomeView';
import { userRoutes } from '@/router/routes-user';
import { statRoutes } from '@/router/routes-stat';
import { error404Route } from '@/router/error404';


Vue.use(Router);


const router = new Router({
  mode: 'history',

  // base: process.env.BASE_URL,

  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
  ],
});

router.addRoutes(userRoutes);
router.addRoutes(statRoutes);
router.addRoutes([error404Route]);


export default router;

