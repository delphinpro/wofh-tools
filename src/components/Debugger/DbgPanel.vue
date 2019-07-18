<script>/**
 * WofhTools
 * DbgPanel.vue
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

export default {
    props: {
        showGrid: Boolean,
        showRhythm: Boolean,
        showOutline: Boolean,
    },

    data: () => ({}),
};
</script>

<template>
    <div class="dbg-panel onStart">
        <div class="dbg-panel__button">
            <div class="dbg-checkbox">
                <label>
                    <input :checked="showGrid" @change="$emit('switch-grid', $event)" type="checkbox">
                    <i></i><span>Показать сетку</span>
                </label>
            </div>
        </div>
        <div class="dbg-panel__button">
            <div class="dbg-checkbox">
                <label>
                    <input :checked="showRhythm" @change="$emit('switch-rhythm', $event)" type="checkbox">
                    <i></i><span>Показать ритм</span>
                </label>
            </div>
        </div>
        <div class="dbg-panel__button">
            <div class="dbg-checkbox">
                <label>
                    <input :checked="showOutline" @change="$emit('switch-outline', $event)" type="checkbox">
                    <i></i><span>Показать элементы</span>
                </label>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .dbg-panel {
        user-select: none;
        position: fixed;
        background: rgba(6, 11, 17, 0.95);
        color: lime;
        padding: 1rem 1rem 1rem 50px;
        z-index: $MAX_INT32;
        min-height: 140px;
        max-height: 80vh;
        overflow: auto;
        transition: opacity 0.15s ease;
        left: 0;
        bottom: 0;
        width: 100%;

        &::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 18px;
            cursor: move;
            border-radius: 6px 0 0 6px;
        }

        &__button {
            padding: 0 3px;
        }

        &, * {
            box-sizing: border-box;
        }
    }

    .dbg-checkbox {
        $color: white;
        $color-checked: lime;
        color: $color;

        label {
            display: inline-flex;
            align-items: flex-start;
            vertical-align: top;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        input {
            position: absolute;
            left: -9999px;
        }

        i {
            flex-shrink: 0;
            width: 12px;
            height: 12px;
            border: 1px solid $color;
            padding: 2px;
            background: transparent;
            background-clip: content-box;
            transition: 0.25s ease;
        }

        input:checked + i {
            border-color: $color-checked;
            background-color: $color-checked;
        }

        span {
            padding-left: 0.5em;
            line-height: 12px;
        }
    }
</style>
