<script>/*!
 * WofhTools
 * Component: App.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import AppNavbar from '@/components/AppNavbar';
import AppFooter from '@/components/AppFooter';
import Loading from '@/components/Loading';
import { mapGetters } from 'vuex';
import { LOADING_DOWN, LOADING_UP } from '@/store/actions';
import { AUTH_LOGOUT } from '@/store/actions/auth';


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
