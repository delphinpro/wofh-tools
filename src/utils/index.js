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
  return b.started_at - a.started_at;
}
