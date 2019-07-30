<script>/*!
 * WofhTools
 * Component: AppFooter.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    data: () => ({}),
    computed: {
        projectName() { return this.$store.getters.projectName; },
        projectVer() { return this.$store.getters.projectVer; },
    },
};
</script>

<template>
    <div class="app-footer">
        <div class="app-footer__container container-fluid">
            <div class="app-footer__section">
                <p>
                    © {{projectName}}, v{{projectVer}},
                    <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
                    2013—2019 гг.
                </p>
                <p>
                    <a href="#">Last update: xx.xx.xxxx, xx:xx</a>
                </p>
            </div>
            <div class="app-footer__section">
                <img src="../../assets/main/counter.png" alt="">
            </div>
            <div class="app-footer__section text-md-right">
                <ul>
                    <li><a href="#">Обратная связь</a></li>
                    <li><a href="#">Карта сайта</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/AppFooter.scss"></style>
