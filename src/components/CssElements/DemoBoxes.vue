<script>/*!
 * WofhTools
 * Component: DemoBoxes.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Box from '@/components/Box';


export default {
    name: 'DemoBoxes',

    components: {
        Box,
    },

    data: () => ({
        boxes: [{
            type: 'default',
            title: 'Box default',
            icon: '',
        }, {
            type: 'primary',
            title: 'Box primary',
            icon: 'tag',
        }, {
            type: 'info',
            title: 'Box info',
            icon: 'tag',
        }, {
            type: 'success',
            title: 'Box success',
            icon: 'tag',
        }, {
            type: 'warning',
            title: 'Box warning',
            icon: 'tag',
        }, {
            type: 'danger',
            title: 'Box danger',
            icon: 'tag',
        }],
    }),
};
</script>

<template>
    <div>
        <div class="row" v-if="boxes.length">
            <div class="col-md-4" v-for="box in boxes">
                <Box v-bind="box">
                    <p>Box content</p>
                </Box>
            </div>
        </div>
        <pre>
&lt;Box
    type="primary"
    title="Box primary"
    icon="tag"
&gt;Box Content&lt;/Box&gt;</pre>
    </div>
</template>
