/*!
 * WofhTools
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default function trimRootPath(path) {
    const root = 'D:/dev/projects/wofh-tools/wofh-tools.project';
    return path.replace(/\\/g, '/').replace(root, '');
}
