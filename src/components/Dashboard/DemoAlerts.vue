<script>/*!
 * WofhTools
 * DemoAlerts.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import Alert from '@/components/Alert';


const content = 'A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.';

export default {
    name: 'DemoAlerts',

    components: {
        Alert,
    },

    data: () => ({
        content,
        alerts: [
            { title: 'Default', content, icon: null },
            { title: 'Info', content, icon: 'info' },
            { title: 'Success', content, icon: 'check' },
            { title: 'Warning', content, icon: 'exclamation-triangle' },
            { title: 'Danger', content, icon: 'ban' },
        ],
    }),

    methods: {
        getClass(title, callout = false) {
            return `alert alert-${title.toLowerCase()} ${callout ? 'alert-callout' : 'alert-dismissible'}`;
        },
        getIconClass: icon => `icon fa fa-${icon}`,
    },
};
</script>

<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <h3 class="type-title">Alerts</h3>
                <Alert
                    v-for="item in alerts" :key="item.title"
                    :type="item.title.toLowerCase()"
                    :title="item.title + ' alert!'"
                    :icon="item.icon"
                    dismiss
                >{{item.content}}
                </Alert>
            </div>
            <div class="col-md-6">
                <h3 class="type-title">Callouts</h3>
                <Alert
                    v-for="item in alerts" :key="item.title"
                    :type="item.title.toLowerCase()"
                    :title="`I am a ${item.title.toLowerCase()} callout!`"
                    callout
                >{{item.content}}
                </Alert>
            </div>
        </div>
    </div>
</template>
