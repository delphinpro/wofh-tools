/*!
 * WofhTools
 * File: router/routes-stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import StatisticView from '@/views/StatisticView';
import StatWorldView from '@/views/Stat/StatWorldView';


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
