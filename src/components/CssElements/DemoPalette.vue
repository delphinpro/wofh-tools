<script>/*!
 * WofhTools
 * DemoPalette.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Box from '@/components/Box';


export default {
    name: 'DemoPalette',

    components: {
        Box,
    },

    data: () => ({
        palette: [
            ['aqua', '#00c0ef'],
            ['light-blue', '#3c8dbc'],
            ['blue', '#0073b7'],
            ['navy', '#001f3f'],
            ['teal', '#39cccc'],
            ['lime', '#01ff70'],
            ['olive', '#3d9970'],
            ['green', '#00a65a'],
            ['yellow', '#f39c12'],
            ['orange', '#ff851b'],
            ['red', '#dd4b39'],
            ['maroon', '#d81b60'],
            ['fuchsia', '#f012be'],
            ['purple', '#605ca8'],
            ['black', '#111'],
        ],
        semantic: [
            ['light-blue', '#3c8dbc', '$color-primary'],
            ['aqua', '#00c0ef', '$color-info'],
            ['green', '#00a65a', '$color-success'],
            ['yellow', '#f39c12', '$color-warning'],
            ['red', '#dd4b39', '$color-danger'],
            ['gray', '#d2d6de', 'Gray'],
        ],
    }),
};
</script>

<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <Box title="Color palette">
                    <div class="color-palette-set" v-for="item in palette">
                        <h4>$palette-{{item[0]}}</h4>
                        <div :class="('bg-'+item[0]+' disabled')"><span>Disabled</span></div>
                        <div :class="('bg-'+item[0])"><span>{{item[1]}}</span></div>
                        <div :class="('bg-'+item[0]+'-active')"><span>Active</span></div>
                    </div>
                </Box>
            </div>
            <div class="col-sm-6">
                <Box title="Semantic colors">
                    <div class="color-palette-set" v-for="item in semantic">
                        <h4>{{item[2]}}</h4>
                        <div :class="('bg-'+item[0]+' disabled')"><span>Disabled</span></div>
                        <div :class="('bg-'+item[0])"><span>{{item[1]}}</span></div>
                        <div :class="('bg-'+item[0]+'-active')"><span>Active</span></div>
                    </div>
                </Box>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
    .color-palette-set {
        display: flex;
        font-size: 12px;

        &:not(:last-child) {
            margin-bottom: 20px;
        }

        > * {
            flex-shrink: 0;
            display: flex;
            align-items: center;
        }

        $w: 33%;

        > h4 {
            width: $w;
            margin: 0;
            height: 30px;
            font-size: 15px;
            font-family: monospace;
        }

        > div {
            width: ((100% - $w) / 3);
            justify-content: center;

            &:nth-child(3) {
                font-family: monospace;
            }
        }
    }
</style>
