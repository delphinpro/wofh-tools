<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
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
    <div class="footer">
        <div class="footer__container container-fluid">
            <div class="footer__section">
                <p>
                    © {{projectName}}, v{{projectVer}},
                    <a href="http://delphinpro.ru" target="_blank" rel="noreferrer">delphinpro</a>,
                    2013—2019 гг.
                </p>
                <p>
                    <a href="#">Last update: xx.xx.xxxx, xx:xx</a>
                </p>
            </div>
            <div class="footer__section">
                <img src="../assets/main/counter.png" alt="">
            </div>
            <div class="footer__section text-md-right">
                <ul>
                    <li><a href="#">Обратная связь</a></li>
                    <li><a href="#">Карта сайта</a></li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .footer {
        @include font(11px, 14px);
        width: 100%;
        background: darken($sidebar-dark-bg, 3%);
        box-shadow: 0 4px 4px rgba(0, 0, 0, .2) inset;

        &__container {
            @include media-breakpoint-up(md) {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        }

        &__section {
            padding: 1em 0;
        }

        p {
            margin: 0;
        }

        a {
            color: inherit;

            &:hover {
                color: lighten($wt-main-color, 10%);
            }
        }

        ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: inline-flex;
        }
    }
</style>
