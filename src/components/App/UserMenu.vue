<!--
  WofhTools
  Component: UserMenu.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { AUTH_LOGOUT } from '@/store/modules/store-auth';
import { mapGetters, mapState } from 'vuex';

import { mdiAccountPlus, mdiLoginVariant } from '@quasar/extras/mdi-v5';
// const includeCssDemo = (process.env.NODE_ENV === 'development') && (process.env.WEBPACK_TARGET !== 'node');

export default {
  name: 'UserMenu',

  data: () => ({
    mdiAccountPlus,
    mdiLoginVariant,
    // includeCssDemo,
    username    : 'Сергей delphinpro',
    usermenuOpen: false,
  }),

  computed: {
    ...mapGetters([
      'getProfile',
      'isAuth',
      'isProfileLoaded',
    ]),
    ...mapState({
      name: state => `${state.user.profile.title} ${state.user.profile.name}`,
    }),
  },

  methods: {
    logout() {
      this.$store.dispatch(AUTH_LOGOUT).then(() => this.$router.push('/login'));
    },
  },
};
</script>

<template>
  <div class="flex self-stretch">
    <QSeparator vertical/>
    <QBtn stretch flat no-caps label="Sign Up" :icon="mdiAccountPlus" to="/registration"/>
    <QSeparator vertical/>
    <QBtn stretch flat no-caps label="Sign In" :icon="mdiLoginVariant" to="/login"/>
  </div>
  <!--
    <ul class="nav-menu">
      <template v-if="isAuth">
        <li class="nav-menu__item">
          <router-link class="nav-menu__link" to="/dashboard">
            <FaIcon class="nav-menu__icon" name="cogs" scale="1.2"/>
            <span>Dashboard</span>
          </router-link>
        </li>
        <li @mouseleave="usermenuOpen=false" class="nav-menu__item">
          <span @click.prevent="usermenuOpen=!usermenuOpen" class="nav-menu__link">
            <FaIcon class="nav-menu__icon" name="user" scale="1"/>
            <span>{{ username }}</span>
          </span>
          <ul @click="usermenuOpen=false" class="usermenu" v-if="usermenuOpen">
            <li class="usermenu__item" v-if="includeCssDemo">
              <router-link class="usermenu__link" to="/dashboard/css/type">
                <span class="usermenu__icon"></span>
                <span>CSS Elements</span>
              </router-link>
            </li>
            <li class="usermenu__item">
              <router-link :to="{name:'profile'}" class="usermenu__link">
                <span class="usermenu__icon"></span>
                <span>Profile</span>
              </router-link>
            </li>
            <li class="usermenu__item">
               <span @click="logout" class="usermenu__link">
                 <span class="usermenu__icon">
                   <FaIcon name="sign-out-alt"/>
                 </span>
                 <span>Sign out</span>
               </span>
            </li>
          </ul>
        </li>
      </template>
      <template v-if="!isAuth">
        <li class="nav-menu__item">
          <router-link class="nav-menu__link" to="/registration">
            <FaIcon class="nav-menu__icon" name="user-plus"/>
            <span>Sign up</span>
          </router-link>
        </li>
        <li class="nav-menu__item">
          <router-link class="nav-menu__link" to="/login">
  &lt;!&ndash;          <FaIcon class="nav-menu__icon" name="sign-in-alt"/>&ndash;&gt;
            <q-icon :name="mdiAccountPlus"></q-icon>
            <span>Sign in</span>
          </router-link>
        </li>
      </template>
    </ul>
  -->
</template>
