<template>
  <div>
    <h1>{{ $t("login.page_title") }}</h1>

    <div class="w-50 m-auto text-left">

      <div v-if="checkForm.length || serverErrors.length">
        <div class="alert alert-danger" role="alert" v-for="error in checkForm">{{ error }}</div>
        <div class="alert alert-danger" role="alert" v-for="error in serverErrors">{{ error }}</div>
      </div>

      <div class="alert alert-success" role="alert" v-if="$store.state.isAuthenticated">
        {{ $t("login.notification_logged_id") }}
      </div>

      <div class="form-group">
        <label for="login-email">{{ $t("login.form_field_email") }}: <span class="required">*</span></label>
        <input v-model="user.email" type="email" class="form-control" id="login-email" aria-describedby="emailHelp" :placeholder="$t('login.form_field_email_placeholder')">
      </div>

      <div class="form-group">
        <label for="login-password">{{ $t("login.form_field_password") }}: <span class="required">*</span></label>
        <input v-model="user.password" type="password" class="form-control" id="login-password" :placeholder="$t('login.form_field_password_placeholder')">
      </div>

      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary"
                @click="doLogin()"
                @keyup.enter="doLogin()"
                :disabled="buttonDisabled">
          <span v-if="buttonDisabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $t("login.button_login") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                buttonDisabled: false,
                autoCheckForm: false,
                errors: [],
                serverErrors: [],
                user: {
                    email: '',
                    password: ''
                }
            };
        },
        created: function () {
            if(this.$store.state.isAuthenticated) {
                this.$router.push('dashboard');
            }
        },
        computed: {
            checkForm: function (e) {

                this.errors = [];

                if(!this.autoCheckForm) {
                    return this.errors;
                }

                // Check email
                if (!this.user.email.trim()) {
                    this.errors.push(this.$t("login.form_field_email_error"));
                }
                else if (!this.validEmail(this.user.email)) {
                    this.errors.push(this.$t("login.form_field_email_error_correct"));
                }

                // Check password
                if (!this.user.password.trim()) {
                    this.errors.push(this.$t("login.form_field_password_error"));
                }

                return this.errors;
            }
        },
        methods: {
            async doLogin() {
                // activate compute to check form
                this.autoCheckForm = true;

                if(this.checkForm.length) {
                    return;
                }

                this.serverErrors = [];
                this.buttonDisabled = true;

                this.$Progress.start();
                this.$http.post('auth/login', this.user)
                    .then(response => {
                        // Set user data
                        this.$store.state.isAuthenticated = true;
                        this.$store.state.token.access = response.data.result.access_token;
                        this.$store.state.token.type = response.data.result.token_type;
                        this.$store.state.token.expires_at = response.data.result.expires_at;

                        this.$store.commit('syncLocalStorage');
                        this.getUserProfile();
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    });

            },
            async getUserProfile() {

                this.$http.get('user/me')
                    .then(response => {
                        this.$Progress.finish();

                        this.$store.state.user.name = response.data.result.name;
                        this.$store.state.user.email = response.data.result.email;
                        this.$store.state.user.role = response.data.result.role;
                        this.$store.state.user.preferred_working_hour_per_day = response.data.result.preferred_working_hour_per_day;

                        this.$store.commit('syncLocalStorage');

                        let path = 'tasks';

                        if(response.data.result.role == 'manager') {
                            path = 'users';
                        }
                        else if(response.data.result.role == 'admin') {
                            path = 'dashboard';
                        }

                        this.$router.push(path)

                    }, (response) => {
                        this.$Progress.fail()
                    });
            },
            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        }
    }
</script>
<style>

</style>
