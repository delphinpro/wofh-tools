<script>/*!
 * WofhTools
 * Alert.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'Alert',

    props: {
        type: { type: String, default: 'default' },
        title: { type: String },
        icon: { type: String },
        dismiss: { type: Boolean, default: false },
        callout: { type: Boolean, default: false },
    },

    computed: {
        baseClass() {
            return {
                alert_info: this.type === 'info',
                alert_success: this.type === 'success',
                alert_warning: this.type === 'warning',
                alert_danger: this.type === 'danger',
                alert_callout: this.callout,
                alert_dismissable: this.dismissable,
            };
        },
        iconClass() {
            return this.icon ? `fa fa-${this.icon}` : null;
        },
        dismissable() {
            return this.dismiss && !this.callout;
        },
    },
};
</script>

<template>
    <div class="alert" :class="baseClass">
        <button class="close" type="button" v-if="dismissable">×</button>
        <div class="alert__header" v-if="title">
            <i class="alert__icon" :class="iconClass" v-if="icon"></i>
            <span class="alert__title" v-text="title"></span>
        </div>
        <div class="alert__content" v-if="$slots.default">
            <slot></slot>
        </div>
    </div>
</template>
