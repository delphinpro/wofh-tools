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
  ROUTE_STAT_COUNTRIES,
  ROUTE_STAT_COUNTRY,
  ROUTE_STAT_PLAYER,
  ROUTE_STAT_PLAYERS,
  ROUTE_STAT_TOWN,
  ROUTE_STAT_TOWNS,
  ROUTE_STAT_WORLD,
} from '@/constants';
import PageStatPlayers from '@/pages/PageStatPlayers';
import PageStatPlayer from '@/pages/PageStatPlayer';

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
            components: { default: PageStatWorld },
          },
          {
            path      : ':sign/countries',
            name      : ROUTE_STAT_COUNTRIES,
            components: { default: PageStatPlayers },
          },
          {
            path      : ':sign/countries/:id',
            name      : ROUTE_STAT_COUNTRY,
            components: { default: PageStatPlayer },
          },
          {
            path      : ':sign/players',
            name      : ROUTE_STAT_PLAYERS,
            components: { default: PageStatPlayers },
          },
          {
            path      : ':sign/players/:id',
            name      : ROUTE_STAT_PLAYER,
            components: { default: PageStatPlayer },
          },
          {
            path      : ':sign/towns',
            name      : ROUTE_STAT_TOWNS,
            components: { default: PageStatPlayers },
          },
          {
            path      : ':sign/towns/:id',
            name      : ROUTE_STAT_TOWN,
            components: { default: PageStatPlayer },
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
