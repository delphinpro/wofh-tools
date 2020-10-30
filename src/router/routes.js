/*!
 * WofhTools
 * File: router/routes.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import MainLayout from '@/layouts/MainLayout';
import StatLayout from '@/layouts/StatLayout';
import PageIndex from '@/pages/PageIndex';
import PageStatIndex from '@/pages/PageStatIndex';
import PageStatWorld from '@/pages/PageStatWorld';
import {
  ROUTE_HOME,
  ROUTE_STAT,
  ROUTE_STAT_WORLD,
} from '@/constants';
import SideStat from '@/components/Side/SideStat';

const routes = [
  {
    path     : '/',
    component: MainLayout,
    children : [
      {
        path     : '',
        name     : ROUTE_HOME,
        component: PageIndex,
      },
      {
        path      : 'stat',
        components: { default: StatLayout },
        children  : [
          {
            path      : '',
            name      : ROUTE_STAT,
            components: { default: PageStatIndex },
          },
          {
            path      : ':sign',
            name      : ROUTE_STAT_WORLD,
            components: { default: PageStatWorld, right: SideStat },
          },
        ],
      },

      // Always leave this as last one,
      // but you can also remove it
      {
        path     : '*',
        component: () => import('@/pages/Error404.vue'),
      },
    ],
  },
];

export default routes;
