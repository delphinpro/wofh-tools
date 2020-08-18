/*!
 * WofhTools
 * File: utils/index.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export function ucFirst(str) {
  if (!str || typeof str !== 'string') return str;
  return str[0].toUpperCase() + str.slice(1);
}

export function cbSortWorldsByStarted(a, b) {
  if (a.started_at === b.started_at) return 0;
  return a.started_at < b.started_at ? 1 : -1;
}
