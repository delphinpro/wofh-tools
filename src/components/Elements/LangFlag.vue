<!--
  WofhTools
  File: LangFlag.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'LangFlag',

  props: {
    flag: { type: String },
    size: { type: Number, default: 24 },
  },
};
</script>

<template>
  <img class="LangFlag"
    v-if="flag"
    :src="'/images/icons/'+flag+'.svg'"
    :alt="flag"
    :height="size*341.4/512"
    :width="size"
  >
</template>

<style lang="scss">
.LangFlag {
  vertical-align: middle;
}
</style>
