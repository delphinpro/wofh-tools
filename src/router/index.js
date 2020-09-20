/*!
 * WofhTools
 * File: router/index.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Vue from 'vue';
import Router from 'vue-router';
import ViewHome from '@/views/ViewHome';
import Error404 from '@/views/ViewError404';
import { userRoutes } from '@/router/groups/user.route';
import { statRoutes } from '@/router/groups/stat.route.js';
import { ROUTE_HOME } from '@/constants.js';

Vue.use(Router);

const router = new Router({
  mode: 'history',

  // base: process.env.BASE_URL,

  routes: [
    {
      path     : '/',
      name     : ROUTE_HOME,
      component: ViewHome,
    },
  ],
});

router.addRoutes(userRoutes);
router.addRoutes(statRoutes);
router.addRoutes([
  {
    path     : '*',
    component: Error404,
  },
]);

export default router;

