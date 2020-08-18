/*!
 * WofhTools
 * File: router/routes-stat.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import StatisticView from '@/views/StatisticView';
import StatWorldView from '@/views/Stat/StatWorldView';


/** @var Array<RouteConfig> */
export const statRoutes = [
  {
    path: '/stat',
    name: 'stat',
    component: StatisticView,
    meta: {
      crumbsText: 'Статистика',
    },
    children: [
      {
        path: ':sign',
        name: 'statWorld',
        component: StatWorldView,
        meta: {
          crumbsGetter: 'currentWorld',
          crumbsText: world => world.signU,
        },
      },
    ],
  },
];
