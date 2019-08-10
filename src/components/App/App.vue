<script>/*!
 * WofhTools
 * Component: App.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import AppNavbar from '@/components/App/AppNavbar';
import AppFooter from '@/components/App/AppFooter';
import Loading from '@/components/Widgets/Loading';
import { mapGetters } from 'vuex';
import { LOADING_DOWN, LOADING_UP } from '@/store';
import { AUTH_LOGOUT } from '@/store/modules/store-auth';


export default {
    components: {
        AppNavbar,
        AppFooter,
        Loading,
    },

    data: () => ({}),

    computed: {
        ...mapGetters([
            'loading',
        ]),
    },

    mounted() {
        this.$store.commit(LOADING_DOWN);

        /* Axios: REQUEST */
        this.axios.interceptors.request.use((config) => {
            this.$store.commit(LOADING_UP);
            return config;
        });

        /* Axios: RESPONSE */
        this.axios.interceptors.response.use(this.interceptorResponseSuccess, this.interceptorResponseFailed);
    },

    methods: {

        interceptorResponseSuccess(response) {
            this.$store.commit(LOADING_DOWN);
            if (response.status === 401) {
                this.$store.dispatch(AUTH_LOGOUT);
                this.$router.push({ name: 'login' });
            }
            return response;
        },

        interceptorResponseFailed(error) {
            this.$store.commit(LOADING_DOWN);
            return Promise.reject(error);
        },

    },
};
</script>

<template>
    <div class="app">
        <AppNavbar class="app__navbar"/>
        <router-view class="app__main"/>
        <AppFooter class="app__footer"/>
        <div class="app__loader" v-if="loading">
            <Loading/>
        </div>
    </div>
</template>

<style lang="scss" src="../@css/App.scss"></style>
