<!--
  WofhTools
  Components: WorldCard.vue
  © 2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import {
  mdiCalendarRefresh,
  mdiCalendarRemove,
  mdiCheck,
  mdiChevronRight,
  mdiClockOutline,
  mdiClose,
} from '@quasar/extras/mdi-v5';
import LangFlag from '@/components/Elements/LangFlag';
import { ROUTE_STAT_WORLD } from '@/constants.js';

export default {
  name: 'WorldCard',

  components: {
    LangFlag,
  },

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
        name  : ROUTE_STAT_WORLD,
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
          <LangFlag :flag="world.serverCountryFlag" :size="30"/>
        </q-badge>
      </q-card-section>
    </q-card-section>
    <q-separator/>
    <q-card-actions>
      <q-icon :name="world.statUpdatedAt ? mdiCalendarRefresh : mdiCalendarRemove" size="1.6em" class="q-mr-sm"/>
      <i v-if="!link" v-text="noDataText"></i>
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
  background-color: $wt-bg-box;
  &__sign {
    @extend %font-family-head;
    background: $wt-color-soft-positive;
    color: #fff;
    font-size: 22px;
    &:first-letter { text-transform: uppercase; }
  }
  &_closed &__sign {
    background: $wt-color-negative;
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
