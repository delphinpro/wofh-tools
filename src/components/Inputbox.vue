<script>/*!
 * WofhTools
 * Inputbox.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'Inputbox',

    props: {
        type: { type: String, default: 'text' },
        id: { type: String, default: null },
        name: { type: String, default: null },
        value: { default: '' },
        placeholder: { type: String, default: null },
        disabled: { type: Boolean, default: false },
        readonly: { type: Boolean, default: false },
        cols: { type: String, default: null },
        rows: { type: String, default: null },
        label: { type: String, default: null },
        labelIcon: { type: String, default: null },
        help: { type: String, default: null },
        status: { type: String, default: null },
    },

    computed: {
        cid() {
            return this.id || `inputbox-${this._uid}`;
        },
        isTextarea() {
            return this.type === 'textarea';
        },
        rootClasses() {
            return {
                'inputbox_status_success': this.status === 'success',
                'inputbox_status_warning': this.status === 'warning',
                'inputbox_status_danger': this.status === 'danger',
            };
        },
    },

    methods: {},
};
</script>

<template>
    <div class="inputbox" :class="rootClasses">
        <label
            class="inputbox__label"
            :for="cid"
            v-if="label"
        ><i class="inputbox__label-icon fa" :class="'fa-'+labelIcon" v-if="labelIcon"></i>{{label}}</label>
        <input
            class="inputbox__control inputbox__input"
            :id="cid"
            :name="name"
            :value="value"
            :type="type"
            :placeholder="placeholder"
            :disabled="disabled"
            :readonly="readonly"
            @input="$emit('input', $event)"
            v-if="!isTextarea"
        >
        <textarea
            class="inputbox__control inputbox__textarea"
            :id="cid"
            :name="name"
            :value="value"
            :placeholder="placeholder"
            :disabled="disabled"
            :readonly="readonly"
            :cols="cols"
            :rows="rows"
            @input="$emit('input', $event)"
            v-if="isTextarea"
        ></textarea>
        <div class="inputbox__help" v-if="help" v-html="help"></div>
    </div>
</template>
