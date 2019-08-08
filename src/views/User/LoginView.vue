<script>/*!
 * WofhTools
 * View: LoginView.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import { AUTH_REQUEST } from '@/store/actions/auth';
import Box from '@/components/Widgets/Box';


export default {
    name: 'login',

    components: {
        Box,
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

        async login() {
            let { username, password } = this;
            this.$toast.removeAll();
            this.form = null;
            let { token, validation } = await this.$store.dispatch(AUTH_REQUEST, { username, password });
            if (validation) this.form = validation.fields;
            if (token) this.$router.push({ name: 'profile' });
        },

        fieldHelp(name) {
            if (this.form && this.form[name] && !this.form[name]['isValid'] && this.form[name]['message']) {
                return this.form[name]['message'];
            }
            return null;
        },

        fieldStatus(name) {
            if (this.form && this.form[name] && !this.form[name]['isValid']) {
                return 'danger';
            }
            return null;
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
                        :help="fieldHelp('username')"
                        :status="fieldStatus('username')"
                        v-model="username"
                    />
                    <Inputbox
                        class="form-group"
                        label="Password"
                        type="password"
                        placeholder="Password"
                        addon-icon="key"
                        addon-position="end"
                        :help="fieldHelp('password')"
                        :status="fieldStatus('password')"
                        v-model="password"
                    />
                    <div class="form-group">
                        <button class="btn btn_primary" type="submit">
                            <FaIcon name="sign-in-alt"/>
                            Login
                        </button>
                    </div>
                </form>
            </Box>
        </div>
    </div>
</template>
