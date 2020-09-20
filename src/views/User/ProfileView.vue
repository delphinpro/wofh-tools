<!--
  WofhTools
  File: ProfileView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { USER_REQUEST } from '@/store/modules/user.store';


export default {
  name: 'ProfileView',

  data: () => ({
    user: {
      id: null,
      email: null,
      username: null,
      createdAt: null,
      updatedAt: null,
      sex: null,
      status: null,
      lang: null,
      avatar: null,
      verified: null,
      password: null,
      password2: null,
    },

    serviceMessage: 'Loading...',
  }),

  computed: {
    pageDescription() {
      return this.user.id ? `${this.user.username} (${this.user.id})` : '';
    },
  },

  mounted() {
    this.getUserData();
  },

  methods: {
    async getUserData() {
      let userdata = await this.$store.dispatch(USER_REQUEST);
      if (userdata) {
        this.user = userdata.user;
      } else {
        this.serviceMessage = 'Error';
      }
    },

    saveUserData() {
      this.axios.post('/user/profile/save', this.user);
    },
  },
};
</script>

<template>
  <div class="container pb-2">
    <PageHeader
      :subtitle="pageDescription"
    />

    <div class="row" v-if="user.id">
      <div class="col-sm-2">
        <FaIcon name="c.user"/>
      </div>
      <div class="col-sm-5">
        <Inputbox
          class="form-group"
          label="Username"
          type="text"
          addon-icon="user"
          v-model="user.username"
        />
        <Inputbox
          class="form-group"
          label="E-mail"
          label-icon="check"
          type="email"
          addon-icon="envelope"
          autocomplete="new-password"
          :disabled="user.id !== 1"
          v-model="user.email"
        />
        <fieldset class="form-group">
          <legend>Пол</legend>

          <RadioButton v-model="user.sex" :value="0">Не указан</RadioButton>
          <RadioButton v-model="user.sex" :value="1">Мужской</RadioButton>
          <RadioButton v-model="user.sex" :value="2">Женский</RadioButton>
        </fieldset>

        <div class="form-group">
          <button class="btn btn_default" @click="saveUserData">Сохранить</button>
        </div>
      </div>
      <div class="col-sm-5">
        <Inputbox
          class="form-group"
          label="Password"
          type="password"
          addon-icon="key"
          autocomplete="new-password"
          v-model="user.password"
        />
        <Inputbox
          class="form-group"
          label="Password confirm"
          type="password"
          addon-icon="key"
          autocomplete="new-password"
          v-model="user.password2"
        />

        <div class="form-group">
          <button class="btn btn_default" @click="saveUserData">Сохранить</button>
        </div>
      </div>
    </div>
    <div v-else v-text="serviceMessage"></div>
    <div class="row">
      <div class="col-sm-4">
        <pre>{{ user }}</pre>
      </div>
    </div>

  </div>
</template>
