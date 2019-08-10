/*!
 * WofhTools
 * File: utils/index.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export function ucFirst(str) {
    if (!str || typeof str !== 'string') return str;
    return str[0].toUpperCase() + str.slice(1);
}

export function cbSortWorldsByStarted(a, b) {
    if (a.started === b.started) return 0;
    return a.started < b.started ? 1 : -1;
}
