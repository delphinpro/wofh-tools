<!--
  WofhTools
  File: StatWorldView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { CURRENT_WORLD, WORLDS_LIST } from '@/store/modules/store-stat';
import InfoBox from '@/components/Widgets/InfoBox';


export default {
  name: 'StatWorldView',

  components: {
    InfoBox,
  },

  data: () => ({
    stat: [],
    countries: [],
  }),


  computed: {
    pageTitle() {
      return this.$store.getters.currentWorld ? `Статистика ${this.$store.getters.currentWorld['signU']}` : '';
    },
    worldAge() {
      return this.$store.getters.currentWorld ? this.$store.getters.currentWorld.nAge : '';
    },
  },

  async mounted() {
    await this.$store.dispatch(WORLDS_LIST);
    await this.$store.dispatch(CURRENT_WORLD, this.$route.params['sign']);
    let { args, commonStat, countries } = await this.getCommonStat(this.$store.getters.currentWorld.sign);
    console.log(args, commonStat, countries);
    this.stat = commonStat[0];
    this.countries = countries;
  },

  methods: {
    getCommonStat(sign) {
      return this.axios.get(`/stat/${sign}`);
    },
    getFlag(flag) {
      return `//st.wofh-tools.project/flags/${this.$store.getters.currentWorld.sign}/${flag}.gif`;
      // return `/flag.php?w=${this.$store.getters.currentWorld.sign}&f=${flag}`;
    },
  },
};
</script>

<template>
  <div>
    <PageHeader :title="pageTitle"/>

    <div class="row">
      <div class="col-lg-9">
        <h2 class="h3">Общая статистика</h2>
        <div class="row">
          <div class="col-md-3 d-flex">
            <div class="info-card info-card_theme_info">
              <FaIcon class="info-card__icon" name="clock"></FaIcon>
              <div class="info-card__title">{{ worldAge }}</div>
              <div class="info-card__content">дней длится раунд</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-card info-card_theme_success">
              <FaIcon class="info-card__icon" name="users"></FaIcon>
              <div class="info-card__title">{{ stat.accountsTotal }}</div>
              <div class="info-card__content">регистраций</div>
            </div>
          </div>
          <div class="col-md-3" v-if="stat.accountsActive">
            <div class="info-card info-card_theme_warning">
              <FaIcon class="info-card__icon" name="user-check"></FaIcon>
              <div class="info-card__title">{{ stat.accountsActive }}</div>
              <div class="info-card__content">активных игроков</div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-card info-card_theme_danger">
              <FaIcon class="info-card__icon" name="flag"></FaIcon>
              <div class="info-card__title">{{ stat.countriesTotal }}</div>
              <div class="info-card__content">стран</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3"></div>
    </div>
    <pre>{{ stat }}</pre>
    <hr>
    <!--        <img width="60" height="40" src="//st.wofh-tools.project/flags/ru3t/d0aacloloagaaaaaaaa.gif" alt="//st.wofh-tools.project/flags/ru3t/d0aacloloagaaaaaaaa.gif">-->
    <hr>
    <div style="display: flex;flex-wrap: wrap;">
      <img
        :src="getFlag(country.countryFlag)" height="40"
        :alt="getFlag(country.countryFlag)"
        width="60"
        v-for="country in countries"
      >
    </div>
    <ul>
      <li v-for="country in countries">
        {{ country.countryId }}
        {{ country.countryTitle }}
        {{ country.countryActive }}
        <kbd>{{ country.countryFlag }}</kbd>
        <img
          :src="getFlag(country.countryFlag)"
          :alt="getFlag(country.countryFlag)"
          height="20"
          width="30"
        >
      </li>
    </ul>
    <pre>{{ countries }}</pre>
  </div>
</template>

<style>

</style>
