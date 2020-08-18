/*!
 * WofhTools
 * File: mergeState.js
 * © 2019-2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export function isServerBundle() {
  try {
    // return global.process.env.VUE_ENV && global.process.env.VUE_ENV === 'server';
  } catch ( e ) { }

  return false;
}

export function mergeState(defaultState, moduleName = 'default') {
  let preloadState = [];

  try {
    if (isServerBundle()) {
      // preloadState = global.__PRELOAD_STATE__['STATE'];
    } else {
      // preloadState = window.__PRELOAD_STATE__ ? window.__PRELOAD_STATE__ : [];
    }
  } catch ( e ) {}

  return {
    ...defaultState,
    ...(preloadState.hasOwnProperty(moduleName) ? preloadState[moduleName] : {}),
  };
}
