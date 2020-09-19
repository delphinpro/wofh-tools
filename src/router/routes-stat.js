/*!
 * WofhTools
 * File: router/routes-stat.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import StatisticView from '@/views/StatisticView';
import StatWorldView from '@/views/Stat/StatWorldView';
import { ucfirst } from '@/utils';
import ViewSidebarStat from '@/views/Sidebar/ViewSidebarStat.vue';

/** @var Array<RouteConfig> */
export const statRoutes = [
  {
    path      : '/stat',
    name      : 'stat',
    components: {
      default: StatisticView,
      // right  : ViewSidebarStat,
    },
    meta      : {
      crumbsText: 'Статистика',
    },
    children  : [
      {
        path     : ':sign',
        name     : 'statWorld',
        components: {
          default: StatWorldView,
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
