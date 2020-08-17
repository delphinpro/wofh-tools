/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export default function trimRootPath(path) {
  const root = 'D:/dev/projects/wofh-tools/wofh-tools.project'; // todo: как-то брать из вне
  return path.replace(/\\/g, '/').replace(root, '');
}
