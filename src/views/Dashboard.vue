<script>/**
 * WofhTools
 * @author      delphinpro <delphinpro@gmail.com>
 * @copyright   copyright © 2019 delphinpro
 * @license     licensed under the MIT license
 */


export default {
    name: 'login',

    data: () => ({
        worlds: [],

        tabs: {
            10: 'RU',
            11: 'RU Speed',
            12: 'RU Test',
            40: 'INT',
            41: 'INT Speed',
            42: 'INT Test',
        },
        activeTabIndex: 10,
    }),

    computed: {
        displayWorlds() {
            return this.worlds.filter(item => {
                return Math.floor(item.id / 1000) === this.activeTabIndex;
            });
        },
    },

    mounted() {
        this.listWorlds();
    },

    methods: {
        listWorlds() {
            this.axios
                .get('/wofh/worlds')
                .then(response => {
                    this.worlds = [...response.data.payload.worlds];
                }).catch(error => {
            });
        },
        checkWorlds() {
            this.axios
                .post('/wofh/check')
                .then(response => {
                    this.worlds = [...response.data.payload.worlds];
                }).catch(error => {
            });
        },
        checkWorlds404() {
            this.axios
                .post('/404/check')
                .then(response => {
                }).catch(error => {
            });
        },
    },
};
</script>

<template>
    <div class="container">
        <h1>Dashboard</h1>

        <button
            type="button"
            @click="checkWorlds"
        >Check worlds
        </button>
        <button
            type="button"
            @click="checkWorlds404"
        >404
        </button>

        <div class="tabs" v-if="worlds.length">
            <div>
                Game servers:
            </div>
            <div class="s">
                <div
                    v-for="(tab, index) in tabs"
                    v-text="tab"
                    class="tab"
                    :class="{active: (+index)===activeTabIndex}"
                    @click="activeTabIndex = +index"
                ></div>
            </div>
        </div>

        <table v-if="displayWorlds.length">
            <thead>
            <tr>
                <th>ID</th>
                <th>sign</th>
                <th>domain</th>
                <th>title</th>
                <th>canreg</th>
                <th>fame</th>
                <th>hard</th>
                <th>langs</th>
                <th>selected</th>
                <th>working</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(world) in displayWorlds" :class="{active: world.working}">
                <td>{{world.id}}</td>
                <td>{{world.sign}}</td>
                <td>{{world.domain}}</td>
                <td>{{world.title}}</td>
                <td>{{world.can_reg}}</td>
                <td>{{world.fame}}</td>
                <td>{{world.hard}}</td>
                <td>{{world.langs}}</td>
                <td>{{world.selected}}</td>
                <td>{{world.working}}</td>
            </tr>
            </tbody>
        </table>
        <div v-else>Пусто</div>
    </div>
</template>

<style lang="scss">
    table {
        th, td {
            border: 1px solid black;
            padding: 3px 10px;
        }

        tr.active {
            color: $color-link;
        }
    }


    .tabs {
        display: flex;
        align-items: center;
        margin: 20px 0;

        > .s {
            margin-left: 10px;
            display: flex;
            border: 1px solid black;
        }
    }

    .tab {
        padding: 0.25em 1em;
        bordeR: 1px solid black;
        cursor: pointer;

        &.active {
            cursor: default;
            background: rgba(#000, 0.2);
        }
    }
</style>
