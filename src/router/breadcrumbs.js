/*!
 * WofhTools
 * File: breadcrumbs.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

import { mdiHome } from '@quasar/extras/mdi-v5';
import {
  ROUTE_HOME,
  ROUTE_STAT,
  ROUTE_STAT_COUNTRIES, ROUTE_STAT_COUNTRY,
  ROUTE_STAT_PLAYER,
  ROUTE_STAT_PLAYERS, ROUTE_STAT_TOWN, ROUTE_STAT_TOWNS,
  ROUTE_STAT_WORLD,
} from '@/constants';
import { ucfirst } from '@/utils';

class Breadcrumbs {
  constructor() {
    this.crumbs = {};
    this.tree = [];
    this.router = null;
    this.store = null;
  }

  for(name, parent, bc = {}, next = null) {
    this.crumbs[name] = {
      name,
      parent,
      label: '???',
      icon : undefined,
      ...bc,
    };
    if (typeof next === 'function') {
      next.call(this, name);
    }
  }

  generate(currentRoute, store) {
    let bc = this.makeSegments(currentRoute, currentRoute.name, store);
    console.log(currentRoute.matched);
    return bc.reverse();
  }

  makeSegments(currentRoute, name, store, bc = []) {
    if (this.crumbs.hasOwnProperty(name)) {
      let label = '???';
      if (typeof this.crumbs[name].label === 'string') label = this.crumbs[name].label;
      if (typeof this.crumbs[name].label === 'function') {
        label = this.crumbs[name].label({
          route: currentRoute,
          store,
        });
      }
      bc.push({
        label,
        name: this.crumbs[name].name,
        icon: this.crumbs[name].icon,
      });
      if (this.crumbs[name].parent) {
        return this.makeSegments(currentRoute, this.crumbs[name].parent, store, bc);
      }
    }
    return bc;
  }
}

const crumbs = new Breadcrumbs();

let bcHome = { label: () => 'Главная', icon: mdiHome };
let bcStat = { label: 'Статистика' };
let bcStatWorld = { label: ({ route }) => ucfirst(route.params.sign) };

crumbs.for(ROUTE_HOME, null, bcHome, function (parent) {
  this.for(ROUTE_STAT, parent, bcStat, function (parent) {
    this.for(ROUTE_STAT_WORLD, parent, bcStatWorld, function (parent) {
    });
  });
});

export default crumbs;
