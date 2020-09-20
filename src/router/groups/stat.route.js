/*!
 * WofhTools
 * File: router/groups/stat.route.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import ViewStatistic from '@/views/ViewStatistic';
import ViewStatWorld from '@/views/Stat/ViewStatWorld';
import { ucfirst } from '@/utils';
import ViewSidebarStat from '@/views/Sidebar/ViewSidebarStat.vue';
import { ROUTE_STAT, ROUTE_STAT_PLAYER, ROUTE_STAT_WORLD } from '@/constants.js';

/** @var Array<RouteConfig> */
export const statRoutes = [
  {
    path      : '/stat',
    name      : ROUTE_STAT,
    components: {
      default: ViewStatistic,
      // right  : ViewSidebarStat,
    },
    meta      : {
      crumbsText: 'Статистика',
    },
    children  : [
      {
        path      : ':sign',
        name      : ROUTE_STAT_WORLD,
        components: {
          default: ViewStatWorld,
          // right: ViewSidebarStat,
        },
        meta     : {
          crumbsGetter: 'currentWorld',
          crumbsText  : world => ucfirst(world.sign),
        },
      },
    ],
  },
];
