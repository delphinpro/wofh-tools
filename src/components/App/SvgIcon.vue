<script>
    export default {
        name: 'SvgIcon',

        props: {
            name: {
                type: String,
                required: true,
            },
            color: {
                type: String,
                default: 'currentColor',
            },
            size: {
                type: [String, Number],
                default: null,
            },
            height: {
                type: [String, Number],
                default: null,
            },
        },

        computed: {
            iconPath() {
                let icon = require(`@/assets/icons/${this.name}.svg`);
                if (Object.prototype.hasOwnProperty.call(icon, 'default')) {
                    icon = icon.default;
                }

                return icon.url;
            },

            className() {
                return 'svg-icon-' + this.name;
            },
        },
    };
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 512 512"
        class="svg-icon"
        :class="className"
        :fill="color"
        :width="size"
        :height="height || size">
        <!--suppress HtmlUnknownAttribute, XmlUnusedNamespaceDeclaration -->
        <use :xlink:href="iconPath" xmlns:xlink="http://www.w3.org/1999/xlink"/>
    </svg>
</template>

<style>
    .svg-icon {
        width: auto;
        height: auto;
        vertical-align: middle;
    }
</style>
