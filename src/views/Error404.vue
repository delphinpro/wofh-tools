<script>/*!
 * WofhTools
 * View: Error404.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import ErrorBox from '@/components/Widgets/ErrorBox';


export default {
    components: {
        ErrorBox,
    },
};
</script>

<template>
    <div class="container page-center">
        <ErrorBox
            theme="info"
            title="Упс! Страница не найдена."
            code="404"
        >
            <p>Вы перешли на страницу, которой не существует, либо она была удалена.</p>
            <p><router-link to="/">Перейти на главную</router-link></p>
        </ErrorBox>
    </div>
</template>
