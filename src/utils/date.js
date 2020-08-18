/*!
 * WofhTools
 * File: date.js
 * © 2019 delphinpro <delphinpro@yandex.ru>
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


export function dateFormat(timestring = null) {
  if (!timestring) return null;

  let date = new Date(timestring * 1000);
  return `${date.getDate()} ${MONTHS[date.getMonth()]} ${date.getFullYear()}`;
}
