/*!
 * WofhTools
 * File: utils/index.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export function cbSortWorldsByStarted(a, b) {
    if (a.started === b.started) return 0;
    return a.started < b.started ? 1 : -1;
}
