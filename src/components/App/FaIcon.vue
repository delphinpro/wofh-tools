<!--
  WofhTools
  File: FAIcon.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
const spriteMap = {
  regular: 'regular',
  r: 'regular',
  solid: 'solid',
  s: 'solid',
  brand: 'brand',
  b: 'brand',
  custom: 'custom',
  c: 'custom',
};

let defaultSpriteName = 'solid';
let errorSpriteName = 'solid';
let errorIconName = 'bug';

export default {
  name: 'FaIcon',

  props: {
    name: String,
  },

  computed: {
    compositeName() {
      let name = this.name.split('.');
      if (name.length === 1) name.splice(0, 0, defaultSpriteName);
      return name;
    },
    mappedName() { return spriteMap[this.compositeName[0]]; },
    isValidName() { return this.compositeName.length === 2 && this.mappedName; },
    spriteName() { return this.isValidName ? this.mappedName : errorSpriteName; },
    iconName() { return this.isValidName ? this.compositeName[1] : errorIconName; },
    link() { return `/assets/${this.spriteName}.svg#${this.iconName}`; },
  },
};
</script>

<template>
  <svg :class="'fa-icon-'+iconName" class="fa-icon">
    <use :xlink:href="link"></use>
  </svg>
</template>
