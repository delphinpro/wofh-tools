/*!
 * WofhTools
 * File: store/stat/getters.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export const allWorlds = state => state.worlds;
export const activeWorlds = state => state.worlds.filter(item => item.working);

export const activeWorldsSortByStarted = (state, getters) => {
  return getters.activeWorlds.sort((a, b) => b.startedAt - a.startedAt);
};

export const closedWorlds = state => state.worlds.filter(item => !item.working);

export const closedWorldsSortByStarted = (state, getters) => {
  return getters.closedWorlds.sort((a, b) => b.startedAt - a.startedAt);
};

export const worldBySign = state => sign => {
  let world = [...state.worlds].filter(w => w.sign === sign);
  if (world.length) return world[0];
};

export const countryById = state => id => 'Тестовая страна (' + id + ')';
export const playerById = state => id => 'Тестовый игрок (' + id + ')';
export const townById = state => id => 'Тестовый город (' + id + ')';
