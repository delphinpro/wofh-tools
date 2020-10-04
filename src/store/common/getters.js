/*!
 * WofhTools
 * File: store/common/getters.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export const projectUpdatedAt = state => state.project.updatedAt ? new Date(state.project.updatedAt) : null;
export const yaCounter = state => state.yaCounter ?? {};
export const yaInformer = state => state.yaInformer ?? {};
