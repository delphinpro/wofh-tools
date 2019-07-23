<script>/*!
 * WofhTools
 * Component: App.vue
 * © 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

import AppNavbar from '@/components/AppNavbar';
import AppFooter from '@/components/AppFooter';
import Loading from '@/components/Loading';


function trimRootPath(path) {
    const root = 'D:/dev/projects/wofh-tools/wofh-tools.project';
    return path.replace(/\\/g, '/').replace(root, '');
}

export default {
    components: {
        AppNavbar,
        AppFooter,
        Loading,
    },

    data: () => ({
        loading: true,
    }),

    computed: {},

    created: function () {
    },

    mounted() {
        this.loading = false;

        /*
         *  REQUEST
         */
        this.axios.interceptors.request.use((config) => {
            this.loading = true;
            console.log('Interceptor Request', config);
            return config;
        }, (error) => {
            console.log('Interceptor Request: error', error);
            // Do something with request error
            return Promise.reject(error);
        });

        /*
         *  RESPONSE
         */
        this.axios.interceptors.response.use((response) => {
            this.loading = false;
            if (response.data.status === false) {
                this.$toast.warn({
                    title: response.data.message,
                    message: `Request [${response.config.method.toUpperCase()}] ${response.config.url}.`,
                });
            } else {
                if (response.data.message) {
                    this.$toast.success({
                        title: 'Success',
                        message: response.data.message,
                    });
                }
            }
            // Do something with response data
            console.log('Interceptor Response', response.data);
            return response;
        }, (error) => {
            console.log('Interceptor Response error', error);
            this.loading = false;
            this.$toast.error({
                title: 'Interceptor Response error',
                message: error.response.data.message,
            });

            let err = error.response.data.error[0];

            err.file = trimRootPath(err.file);
            err.trace = err.trace.map(trimRootPath);

            let message = `Request [${error.config.method.toUpperCase()}] ${error.config.url}.`;
            message += ` ${error.response.data.message}.`;
            message += ` ${err.message}.`;
            message += ` ${err.file}:${err.line}.`;

            this.$toast.error({
                title: error.message,
                message,
            });

            console.log(err.message, err);
            return Promise.reject(error);
        });
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

<style lang="scss">
    .app {
        min-height: 100vh;
        display: flex;
        flex-direction: column;

        &__navbar,
        &__footer {
            width: 100%;
        }

        &__navbar {
        }

        &__main {
            @include padding-leader($navbar-height-rhythm);
            flex-grow: 1;
        }

        &__footer {
        }

        &__loader {
            position: fixed;
            left: 0;
            top: 0;
            z-index: 99998;
            background: rgba(#000, 0.1);
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
        }
    }
</style>
