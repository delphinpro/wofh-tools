<script>/*!
 * WofhTools
 * Component: Loading.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {};
</script>

<template>
    <div class="loader">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
            <path fill="#000"
                d="M25.251,6.461c-10.318,0-18.683,8.365-18.683,18.683h4.068c0-8.071,6.543-14.615,14.615-14.615V6.461z"
            >
                <animateTransform
                    attributeType="xml"
                    attributeName="transform"
                    type="rotate"
                    from="0 25 25"
                    to="360 25 25"
                    dur="0.6s"
                    repeatCount="indefinite"
                />
            </path>
        </svg>
    </div>
</template>

<style lang="scss" src="../@css/Loading.scss"></style>
