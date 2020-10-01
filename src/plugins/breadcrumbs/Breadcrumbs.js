/*!
 * WofhTools
 * Class: Breadcrumbs.js
 * © 2020 delphinpro <delphinpro@yandex.ru>
 * licensed under the MIT license
 */

export class Breadcrumbs {

  constructor(router, store) {
    this.crumbs = {};
    this.router = router;
    this.store = store;
    this._parents = [];
  }

  add(name, bc = {}, next = null) {
    if (this.crumbs.hasOwnProperty(name)) {
      throw Error(`Breadcrumb for <${name}> already exists.`);
    }
    const parent = this._parents.length ? this._parents[this._parents.length - 1] : null;
    this.crumbs[name] = {
      name,
      parent,
      label  : '*',
      icon   : undefined,
      ...bc,
    };
    if (typeof next === 'function') {
      this._parents.push(name);
      next.call(this);
      this._parents.pop();
    }
  }

  generate(currentRoute) {
    let bc = this.makeSegments(currentRoute, currentRoute.name);
    return bc.reverse();
  }

  makeSegments(currentRoute, name, bc = []) {
    if (this.crumbs.hasOwnProperty(name)) {
      let label = '*';
      if (typeof this.crumbs[name].label === 'string') label = this.crumbs[name].label;
      if (typeof this.crumbs[name].label === 'function') {
        label = this.crumbs[name].label({
          route: currentRoute,
          store: this.store,
        });
      }
      bc.push({
        label,
        name: this.crumbs[name].name,
        icon: this.crumbs[name].icon,
      });
      if (this.crumbs[name].parent) {
        return this.makeSegments(currentRoute, this.crumbs[name].parent, bc);
      }
    }
    return bc;
  }
}
