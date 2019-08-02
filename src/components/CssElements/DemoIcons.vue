<script>/*!
 * WofhTools
 * DemoIcons.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import 'vue-awesome/icons/skull-crossbones';
import 'vue-awesome/icons/sync-alt';

export default {
    name: 'DemoIcons',
    data: () => ({
        icons: [
            'skull-crossbones',
            'sync-alt',
        ],
    }),
};
</script>

<template>
    <div>
        <div class="row fa-icon-list">
            <div class="fa-icon-list__item col-sm-2" v-for="icon in icons">
                <FaIcon class="fa-icon-list__icon" :name="icon" :scale="2"/>
                <div class="fa-icon-list__title">{{icon}}</div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .fa-icon-list {
        &__item {
            margin: 0.75rem 0;
            text-align: center;
        }

        &__icon {
        }

        &__title {
            font-family: monospace;
            line-height: 1.2;
            margin-top: 12px;
            opacity: 0.5;
        }
    }
</style>
