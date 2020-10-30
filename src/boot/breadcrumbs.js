/*!
 * WofhTools
 * File: boot/breadcrumbs.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import Breadcrumbs from '@/plugins/breadcrumbs';
import { ucfirst } from '@/helpers';
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

export default ({ Vue, router, store }) => {

  let bcHome = { label: () => 'Главная', icon: 'home' };
  let bcStat = { label: 'Статистика' };
  let bcStatWorld = { label: ({ route }) => ucfirst(route.params.sign) };
  let bcStatCountries = { label: 'Страны' };
  let bcStatCountry = { label: ({ route, store }) => store.getters.countryById(route.params.id) };
  let bcStatPlayers = { label: 'Игроки' };
  let bcStatPlayer = { label: ({ route, store }) => store.getters.playerById(route.params.id) };
  let bcStatTowns = { label: 'Города' };
  let bcStatTown = { label: ({ route, store }) => store.getters.townById(route.params.id) };

  Vue.use(Breadcrumbs, {
    router,
    store,
    elements() {
      this.add(ROUTE_HOME, bcHome, function () {
        this.add(ROUTE_STAT, bcStat, function () {
          this.add(ROUTE_STAT_WORLD, bcStatWorld, function () {
            this.add(ROUTE_STAT_COUNTRIES, bcStatCountries, function () {
              this.add(ROUTE_STAT_COUNTRY, bcStatCountry);
            });
            this.add(ROUTE_STAT_PLAYERS, bcStatPlayers, function () {
              this.add(ROUTE_STAT_PLAYER, bcStatPlayer);
            });
            this.add(ROUTE_STAT_TOWNS, bcStatTowns, function () {
              this.add(ROUTE_STAT_TOWN, bcStatTown);
            });
          });
        });
      });
    },
  });

}


