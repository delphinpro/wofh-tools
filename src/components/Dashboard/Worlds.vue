<script>/*!
 * WofhTools
 * Worlds.vue
 * (c) 2019 delphinpro <delphinpro@gmail.com>
 * licensed under the MIT license
 */

export default {
    name: 'Worlds',

    data: () => ({
        worlds: [],

        tabs: {
            0: 'All',
            10: 'RU',
            11: 'RU Speed',
            12: 'RU Test',
            40: 'INT',
            41: 'INT Speed',
            42: 'INT Test',
        },
        activeTabIndex: 0,
    }),

    computed: {
        displayWorlds() {
            return this.worlds.filter(item => {
                if (this.activeTabIndex === 0) return true;
                return Math.floor(item.id / 1000) === this.activeTabIndex;
            });
        },
    },

    mounted() {
        this.listWorlds();
    },

    methods: {
        async listWorlds() {
            let response = await this.axios.get('/wofh/worlds');
            this.worlds = [...response.data.payload.worlds];
        },

        async checkWorlds() {
            let response = await this.axios.post('/dashboard/worlds/check');
            this.worlds = [...response.data.payload.worlds];
        },
    },
};
</script>

<template>
    <div class="pb-2">
        <div class="mb-1">
            <div class="h4">
                Game servers:
            </div>
            <div class="d-flex">
                <div class="control-group">
                    <button class="btn btn_warning" @click="listWorlds">
                        <FaIcon name="sync-alt"/>
                        Update
                    </button>
                </div>
                <div class="control-group" v-if="worlds.length">
                    <div
                        class="btn"
                        :class="{btn_primary: (+index)===activeTabIndex}"
                        v-for="(tab, index) in tabs"
                        v-text="tab"
                        @click="activeTabIndex = +index"
                    ></div>
                </div>
            </div>
        </div>

        <table class="table mb-1" v-if="displayWorlds.length">
            <thead>
            <tr>
                <th>ID</th>
                <th>Sign</th>
                <th>Title</th>
                <th>Can reg</th>
                <th>Working</th>
                <th>Statistic</th>
                <th>Hidden</th>
                <th>Load stat</th>
                <th>Update stat</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(world) in displayWorlds" :class="{active: world.working}">
                <td><code>{{world.id}}</code></td>
                <td>{{world.sign}}</td>
                <td>{{world.title}}</td>
                <td class="text-center">
                    <FaIcon class="text-green" name="check" v-if="world.can_reg"/>
                </td>
                <td class="text-center">
                    <FaIcon class="text-green" name="check" v-if="world.working"/>
                </td>
                <td class="text-center">
                    <Checkbox v-model="world.statistic" :theme="world.statistic?'warning':null"/>
                </td>
                <td class="text-center">
                    <Checkbox v-model="world.hidden" :theme="world.hidden?'danger':null"/>
                </td>
                <td class="text-right"><samp>{{world.time_of_loaded_stat}}</samp></td>
                <td class="text-right"><samp>{{world.time_of_updated_stat}}</samp></td>
            </tr>
            </tbody>
        </table>
        <Alert type="warning" title="Empty" v-else></Alert>

        <!--<pre>{{worlds}}</pre>-->

        <button
            class="btn"
            type="button"
            @click="checkWorlds"
        >Check worlds</button>
    </div>
</template>

<style lang="scss"></style>
