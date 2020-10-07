/*!
 * WofhTools
 * File: store/common/mutations.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export const updateProjectInfo = (state, payload) => state.project = payload;
export const updateYaCounter = (state, payload) => state.yaCounter = payload;
export const updateYaInformer = (state, payload) => state.yaInformer = payload;

export const showErrorPage = (state, show) => state.showErrorPage = show;
