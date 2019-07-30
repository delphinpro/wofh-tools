<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

export default {
    data: () => ({}),
};
</script>

<template>
    <ul class="top-menu">
        <li class="top-menu__item">
            <router-link class="top-menu__link" to="/stat">Статистика</router-link>
        </li>
        <li class="top-menu__item">
            <a class="top-menu__link" href="/technology">Науки</a>
        </li>
        <li class="top-menu__item">
            <a class="top-menu__link" href="/quests">Квесты</a>
        </li>
        <li class="top-menu__item">
            <a class="top-menu__link" href="/tables">Таблицы</a>
        </li>
    </ul>
</template>

<style lang="scss">
    .top-menu {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;

        &__item {
            @include font(13px);
            font-weight: bold;
            display: flex;
            border-left: 1px solid $second-color-dark;

            &:last-child {
                border-right: 1px solid $second-color-dark;
            }
        }

        &__link {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding-left: 1em;
            padding-right: 1em;
            cursor: pointer;

            &:hover {
                background: $second-color-dark;
                color: #fff;
            }
        }
    }
</style>
