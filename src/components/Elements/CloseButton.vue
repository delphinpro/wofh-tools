<!--
  WofhTools
  File: CloseButton.vue
  © 2019 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'CloseButton',

  methods: {
    onClick(e) {
      this.$emit('click', e);
    },
  },
};
</script>

<template>
  <button class="close-button" type="button" @click="onClick">
    <FaIcon class="close-button__icon" name="times"/>
  </button>
</template>
