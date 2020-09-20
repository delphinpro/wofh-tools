<!--
  WofhTools
  File:
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import {
  mdiClockOutline,
  mdiCalendarRefresh,
  mdiCalendarRemove,
  mdiCheck,
  mdiClose,
  mdiChevronRight,
} from '@quasar/extras/mdi-v5';

export default {
  name: 'WorldCard',

  props: {
    world: Object,
  },

  data: () => ({
    mdiCheck,
    mdiClose,
    mdiCalendarRefresh,
    mdiCalendarRemove,
    mdiChevronRight,
    mdiClockOutline,
  }),

  computed: {
    link() {
      if (!this.world.statUpdatedAt) return null;
      return {
        name  : 'statWorld',
        params: {
          sign: this.world.sign,
        },
      };
    },
    canRegIcon() { return this.world.canReg ? this.mdiCheck : this.mdiClose; },
    canRegText() { return this.world.canReg ? 'Регистрация доступна' : 'Регистрация закрыта'; },
    canRegColor() { return this.world.canReg ? 'positive' : 'negative'; },
    noDataText() { return 'Нет данных статистики'; },
    ageText() { return 'Длительность ' + this.world.localAge; },
  },
};
</script>

<template>
  <q-card class="WorldCard" :class="{'WorldCard_closed':!world.working}">
    <q-card-section horizontal>
      <q-card-section class="WorldCard__sign">
        {{ world.sign }}
      </q-card-section>
      <q-card-section class="col-grow">
        <!--<div class="WorldCard__title">{{ world.title }}</div>-->
        <div class="WorldCard__info">
          <q-icon class="WorldCard__icon" :name="canRegIcon" :color="canRegColor"/>
          <span v-text="canRegText"></span>
        </div>
        <div class="WorldCard__info" v-if="world.age">
          <q-icon class="WorldCard__icon" :name="mdiClockOutline" color="positive"/>
          <span v-text="ageText"></span>
        </div>
        <q-badge floating color="blue-grey-10 shadow-1">
          <img :src="'/assets/images/icons/'+world.serverCountryFlag+'.svg'" :alt="world.serverCountryFlag" width="30"/>
        </q-badge>
      </q-card-section>
    </q-card-section>
    <q-separator/>
    <q-card-actions>
      <q-icon :name="world.statUpdatedAt ? mdiCalendarRefresh : mdiCalendarRemove" size="1.6em" class="q-mr-sm"/>
      <i v-if="!world.statUpdatedAt" v-text="noDataText"></i>
      <template v-else>
        <i>{{ world.localStatUpdatedAt }}</i>
        <q-space/>
        <q-btn flat no-caps color="positive" :icon-right="mdiChevronRight" :to="link">Статистика</q-btn>
      </template>
    </q-card-actions>
  </q-card>
</template>

<style lang="scss">
.WorldCard {
  background-color: $blue-grey-10;
  &__sign {
    background: $green-7;
    color: #fff;
    font-size: 22px;
    font-family: $wt-family-head;
    &:first-letter { text-transform: uppercase; }
  }
  &_closed &__sign {
    background: $color-danger;
  }
  &__title {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    font-weight: bold;
  }
  &__info {
    display: flex;
    align-items: baseline;
  }
  &__icon {
    margin-right: ($space-x-base / 2);
    position: relative;
    bottom: -2px;
  }
}
</style>
