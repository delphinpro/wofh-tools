/*!
 * WofhTools
 * File: router/routes-stat.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Statistic from '@/views/StatisticView';


export const statRoutes = [
    {
        path: '/stat',
        name: 'stat',
        component: Statistic,
    },
];
