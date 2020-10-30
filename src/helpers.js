/*!
 * WofhTools
 * File: helpers.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export function ucfirst(str) {
  if (!str || typeof str !== 'string') return str;
  return str[0].toUpperCase() + str.slice(1);
}

export function cbSortWorldsByStarted(a, b) {
  return b['startedAt'] - a['startedAt'];
}
