<script>/*!
 * WofhTools
 * View: Login.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { AUTH_REQUEST } from '@/store/actions/auth';


export default {
    name: 'login',

    data() {
        return {
            username: '',
            password: '',
        };
    },

    methods: {

        login() {
            const { username, password } = this;
            this.$store.dispatch(AUTH_REQUEST, { username, password }).then(() => {
                this.$router.push('/');
            });
        },

    },
};
</script>

<template>
    <div class="container">
        <form class="login" @submit.prevent="login">
            <h1>Sign in</h1>

            <label>User name</label>
            <input v-model="username" required type="text" placeholder="Login"/>
            <br>

            <label>Password</label>
            <input v-model="password" required type="password" placeholder="Password"/>

            <hr/>
            <button type="submit">Login</button>
        </form>
    </div>
</template>
