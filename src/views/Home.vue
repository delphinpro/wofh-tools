<script>
    import { mapGetters } from 'vuex';
    import HelloWorld from '@/components/HelloWorld';
    import Login from '@/views/Login';


    export default {
        name: 'home',
        components: {
            HelloWorld,
            Login,
        },
        computed: {
        },
    };
</script>

<template>
    <div class="container">
        <div v-if="isAuthenticated">
            <img alt="Vue logo" src="../assets/main/logo.png">
            <HelloWorld msg="Welcome to Your Vue.js App"/>
        </div>
        <div v-if="!isAuthenticated && authStatus !== 'loading'">
            <h1>Welcome to DogeBook !</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi corporis cupiditate eaque ipsam, ipsum
                magnam nostrum odit similique tempora voluptas. Consequatur delectus,
                <a class="" href="#">facilis molestias quam quod</a>
                temporibus? Dolorum quo, veritatis.</p>
        </div>
    </div>
</template>

<style>
</style>
