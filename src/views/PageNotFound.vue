<template>
    <div class="container page-404">
        <h1>Ошибка 404: страница не найдена</h1>
        <p>Вы перешли на страницу, которой не существует, либо она была удалена.</p>
        <p><router-link to="/">Перейти на главную</router-link></p>
    </div>
</template>

<style lang="scss">
    .page-404 {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>
