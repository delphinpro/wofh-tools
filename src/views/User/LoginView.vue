<!--
  WofhTools
  View: LoginView.vue
  © 2019-2020 delphinpro <delphinpro@yandex.ru>
  licensed under the MIT license
-->
<script>
import { AUTH_REQUEST } from '@/store/modules/store-auth';
import Box from '@/components/Widgets/Box';
import { mdiEmail, mdiKey } from '@quasar/extras/mdi-v5';

export default {
  name: 'login',

  components: {
    Box,
  },

  data: () => ({
    mdiEmail,
    mdiKey,

    username: '',
    password: '',

    token: null,
    form : null,

    post: null,
    user: null,
  }),

  methods: {

    async login() {
      let { username, password } = this;
      this.$toast.removeAll();
      this.form = null;
      let { token, validation } = await this.$store.dispatch(AUTH_REQUEST, { username, password });
      if (validation) this.form = validation.fields;
      if (token) this.$router.push({ name: 'profile' });
    },

    fieldHelp(name) {
      if (this.form && this.form[name] && !this.form[name]['isValid'] && this.form[name]['message']) {
        return this.form[name]['message'];
      }
      return null;
    },

    fieldStatus(name) {
      if (this.form && this.form[name] && !this.form[name]['isValid']) {
        return 'danger';
      }
      return null;
    },

  },
};
</script>

<template>
  <div class="container page-center">
    <div style="padding: 40px;width: 500px;margin:auto;">
      <QCard>
        <QForm>
          <q-input color="teal" dense filled v-model="username" label="Email">
            <template v-slot:append>
              <q-icon :name="mdiEmail"/>
            </template>
          </q-input>
          <q-input color="teal" dense filled v-model="password" type="password" label="Password">
            <template v-slot:append>
              <q-icon :name="mdiKey"/>
            </template>
          </q-input>
        </QForm>
      </QCard>
    </div>

    <div class="col-lg-5 no-gutter">
      <Box icon="lock" title="Вход в личный профиль" type="info">
        <form @submit.prevent="login" class="login">
          <Inputbox
            :help="fieldHelp('username')"
            :status="fieldStatus('username')"
            addon-icon="envelope"
            addon-position="end"
            class="form-group"
            label="E-mail"
            placeholder="E-mail"
            type="email"
            v-model="username"
          />
          <Inputbox
            :help="fieldHelp('password')"
            :status="fieldStatus('password')"
            addon-icon="key"
            addon-position="end"
            class="form-group"
            label="Password"
            placeholder="Password"
            type="password"
            v-model="password"
          />
          <div class="form-group">
            <button class="btn btn_primary" type="submit">
              <FaIcon name="sign-in-alt"/>
              Login
            </button>
          </div>
        </form>
      </Box>
    </div>
  </div>
</template>
