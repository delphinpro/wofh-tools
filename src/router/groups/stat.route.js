/*!
 * WofhTools
 * File: router/groups/stat.route.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import ViewStatistic from '@/views/ViewStatistic';
import ViewStatWorld from '@/views/Stat/ViewStatWorld';
import ViewStatPlayers from '@/views/Stat/ViewStatPlayers.vue';
import ViewStatPlayer from '@/views/Stat/ViewStatPlayer.vue';
import { ucfirst } from '@/utils';
import {
  ROUTE_STAT,
  ROUTE_STAT_COUNTRIES, ROUTE_STAT_COUNTRY,
  ROUTE_STAT_PLAYER,
  ROUTE_STAT_PLAYERS, ROUTE_STAT_TOWN, ROUTE_STAT_TOWNS,
  ROUTE_STAT_WORLD,
} from '@/constants.js';

/** @var Array<RouteConfig> */
export const statRoutes = [
  {
    path      : '/stat',
    name      : ROUTE_STAT,
    components: {
      default: ViewStatistic,
      // right  : ViewSidebarStat,
    },
    children  : [
      {
        path      : ':sign',
        name      : ROUTE_STAT_WORLD,
        components: {
          default: ViewStatWorld,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/countries',
        name      : ROUTE_STAT_COUNTRIES,
        components: {
          default: ViewStatPlayers,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/countries/:id',
        name      : ROUTE_STAT_COUNTRY,
        components: {
          default: ViewStatPlayer,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/players',
        name      : ROUTE_STAT_PLAYERS,
        components: {
          default: ViewStatPlayers,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/players/:id',
        name      : ROUTE_STAT_PLAYER,
        components: {
          default: ViewStatPlayer,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/towns',
        name      : ROUTE_STAT_TOWNS,
        components: {
          default: ViewStatPlayers,
          // right: ViewSidebarStat,
        },
      },
      {
        path      : ':sign/towns/:id',
        name      : ROUTE_STAT_TOWN,
        components: {
          default: ViewStatPlayer,
          // right: ViewSidebarStat,
        },
      },
    ],
  },
];
