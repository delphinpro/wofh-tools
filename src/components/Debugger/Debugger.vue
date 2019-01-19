<script>/**
 * WofhTools
 * Debugger.vue
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */

import DbgPanel from './DbgPanel';


const LS_SHOW_GRID = 'dbg.show.grid';
const LS_SHOW_OUTLINE = 'dbg.show.outline';

export default {
    components: {
        DbgPanel,
    },

    data: () => ({
        columnCount: new Array(12),

        showPanel: false,
        showGrid: false,
        showOutline: false,
    }),

    mounted() {
        this.showGrid = !!+localStorage.getItem(LS_SHOW_GRID);
        this.showOutline = !!+localStorage.getItem(LS_SHOW_OUTLINE);

        document.documentElement.dataset.outline = +this.showOutline;
        document.addEventListener('click', () => {
            this.showPanel = false;
        });
    },

    methods: {
        switchGrid(e) {
            this.showGrid = e.target.checked;
            localStorage.setItem(LS_SHOW_GRID, +this.showGrid);
        },

        switchOutline(e) {
            this.showOutline = e.target.checked;
            localStorage.setItem(LS_SHOW_OUTLINE, +this.showOutline);
            document.documentElement.dataset.outline = +this.showOutline;
        },
    },
};
</script>

<template>
    <div class="debugger" @click.stop>
        <div class="dbg-grid" v-if="showGrid">
            <div class="dbg-grid__container">
                <div class="dbg-grid__row">
                    <div class="dbg-grid__col" v-for="i in columnCount"></div>
                </div>
            </div>
        </div>

        <DbgPanel v-if="showPanel"
            :show-grid="showGrid"
            @switch-grid="switchGrid"

            :show-outline="showOutline"
            @switch-outline="switchOutline"
        ></DbgPanel>

        <div class="dbg-main-button"
            @click="showPanel=!showPanel"
        ></div>
    </div>
</template>

<style lang="scss">
    $dev-grid-guides-color: rgba(#4affff, 0.15) !default;
    $z-index-base: $MAX_INT32;

    .debugger {
        font-family: Consolas, monospace;
        font-size: 12px;
    }

    .dbg-main-button {
        $dbg-button-size: 100px;
        position: fixed;
        left: ($dbg-button-size / -2);
        bottom: ($dbg-button-size / -2);
        width: $dbg-button-size;
        height: $dbg-button-size;
        background: red;
        border-radius: 100%;
        transition: 0.3s ease;
        z-index: $z-index-base;
        cursor: pointer;
        opacity: 0.05;

        &:hover {
            opacity: 0.35;
        }

        &.active {
            opacity: 0.5;
        }
    }

    .dbg-grid {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        overflow: hidden;

        &__container {
            @include make-container();
            @include make-container-max-widths();
            height: 100%;
        }

        &__row {
            @include make-row();
            height: 100%;
        }

        &__col {
            @include make-col-ready();
            @include make-col(1);
            border-right: 1px solid $dev-grid-guides-color;
            height: 100%;

            &:first-child {
                border-left: 1px solid $dev-grid-guides-color;
            }

            &::before {
                content: '';
                display: block;
                width: 100%;
                height: 100%;
                border-right: 1px solid $dev-grid-guides-color;
                border-left: 1px solid $dev-grid-guides-color;
            }

            @include media-breakpoint-down(mb) {
                @include make-col(12);
                &:not(:first-child) {
                    display: none;
                }
            }
        }
    }

    @import "./outlines";
</style>
