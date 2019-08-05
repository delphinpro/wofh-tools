/*!
 * WofhTools
 * File: date.js
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

const MONTHS = [
    'января',
    'февраля',
    'марта',
    'апреля',
    'мая',
    'июня',
    'июля',
    'августа',
    'сентября',
    'октября',
    'ноября',
    'декабря',
];


export function dateFormat(timestamp = null) {
    if (!timestamp) return null;

    let date = new Date(timestamp * 1000);
    return `${date.getDate()} ${MONTHS[date.getMonth()]} ${date.getFullYear()}`;
}
