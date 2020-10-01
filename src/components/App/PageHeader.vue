<!--
  WofhTools
  Component: PageHeader.vue
  (c) 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
export default {
  name: 'PageHeader',

  props: {
    title   : { type: String, default: '' },
    subtitle: { type: String, default: '' },
  },

  data: function () {
    return {
      pageTitle   : this.title,
      pageSubtitle: this.subtitle,
    };
  },

  created() {
    this.updateTitles();
  },

  watch: {
    $route() { this.updateTitles(); },
    title() { this.updateTitles(); },
    subtitle() { this.updateTitles(); },
  },

  methods: {
    updateTitles: function () {
      this.pageTitle = this.$router.currentRoute.meta.pageTitle || this.title;
      this.pageSubtitle = this.$router.currentRoute.meta.pageSubtitle || this.subtitle;
    },
  },
};
</script>

<template>
  <div class="PageHeader" v-if="pageTitle">
    <h1><span v-html="pageTitle"></span><small v-html="pageSubtitle" v-if="pageSubtitle"></small></h1>
  </div>
</template>

<style lang="scss">
.PageHeader {
  font-size: $wt-page-header-font-size;
  display: flex;
  align-items: center;
  margin-bottom: 1.2em;

  > h1 {
    font-size: 1em;
    display: flex;
    align-items: baseline;
    flex-grow: 1;
    flex-wrap: wrap;
    margin: 0;

    > small {
      padding-left: 1em;
      opacity: 0.85;
      font-size: 0.625em;
      font-weight: 400;
      line-height: 1;
    }
  }
}
</style>
