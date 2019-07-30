<script>/*!
 * WofhTools
 * View: Login.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { AUTH_REQUEST } from '@/store/actions/auth';
import Inputbox from '@/components/Forms/Inputbox';
import Box from '@/components/Widgets/Box';


export default {
    name: 'login',

    components: {
        Box,
        Inputbox,
    },

    data: () => ({
        username: '',
        password: '',

        token: null,
        form: null,
        post: null,
        user: null,
    }),

    methods: {

        login() {
            let { username, password } = this;
            this.$toast.removeAll();
            this.$store.dispatch(AUTH_REQUEST, { username, password }).then(res => {
                if (res.status) {
                    this.$router.push({ path: '/profile' });
                }
            });
        },

    },
};
</script>

<template>
    <div class="container page-center">
        <div class="col-lg-5 no-gutter">

            <Box type="info" title="Вход в личный профиль" icon="lock">
                <form class="login" @submit.prevent="login">
                    <Inputbox
                        class="form-group"
                        label="E-mail"
                        type="email"
                        placeholder="E-mail"
                        addon-icon="envelope"
                        addon-position="end"
                        v-model="username"
                    />
                    <Inputbox
                        class="form-group"
                        label="Password"
                        type="password"
                        placeholder="Password"
                        addon-icon="key"
                        addon-position="end"
                        v-model="password"
                    />
                    <div class="form-group">
                        <button class="btn btn_primary" type="submit">
                            <i class="fa fa-sign-in-alt"></i>
                            Login
                        </button>
                    </div>
                </form>
            </Box>
        </div>
    </div>
</template>
