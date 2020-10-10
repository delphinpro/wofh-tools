/*!
 * WofhTools
 * File: store/common/state.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { mdiAxisArrow, mdiChartAreaspline, mdiFileTree, mdiTableMultiple } from '@quasar/extras/mdi-v5';

const mainmenu = [
  {
    title  : 'Статистика',
    caption: 'Статистика по игровым мирам',
    icon   : mdiChartAreaspline,
    route  : { path: '/stat' },
  },
];

export default () => ({
  project: {
    name     : 'WofhTools',
    version  : '4',
    updatedAt: null,
  },

  yaCounter : {
    id : 0,
    src: '',
  },
  yaInformer: {
    link: '',
    img : '',
  },

  showErrorPage: false,
  mainmenu,
});
