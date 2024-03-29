<template>
  <div>
    <h1>{{ $t("create_user.page_title") }}</h1>

    <div class="w-50 m-auto text-left">

      <div v-if="checkForm.length || serverErrors.length">
        <div class="alert alert-danger" role="alert" v-for="error in checkForm">{{ error }}</div>
        <div class="alert alert-danger" role="alert" v-for="error in serverErrors">{{ error }}</div>
      </div>

      <div class="alert alert-success" role="alert" v-if="showNotification">
        {{ $t("create_user.notification_registered") }}
      </div>

      <div class="form-group">
        <label for="register-name">{{ $t("create_user.form_field_name") }}: <span class="required">*</span></label>
        <input v-model="user.name" type="text" class="form-control" id="register-name" :placeholder="$t('create_user.form_field_name_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-email">{{ $t("create_user.form_field_email") }}: <span class="required">*</span></label>
        <input v-model="user.email" type="email" class="form-control" id="register-email" aria-describedby="emailHelp" :placeholder="$t('create_user.form_field_email_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-password">{{ $t("create_user.form_field_password") }}: <span class="required">*</span></label>
        <input v-model="user.password" type="password" class="form-control" id="register-password" :placeholder="$t('create_user.form_field_password_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-password-confirmation">{{ $t("create_user.form_field_password_confirmation") }}: <span class="required">*</span></label>
        <input v-model="user.password_confirmation" type="password" class="form-control" id="register-password-confirmation" :placeholder="$t('create_user.form_field_password_confirmation_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-preferred-working-hour-per-day">{{ $t("create_user.form_field_preferred_working_hours") }}: <span class="required">*</span></label> <br />
        <select v-model="hours" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day">
          <option v-for="n in 24" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("create_user.form_field_preferred_working_hours_hours") }}
        <select v-model="minutes" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day-minutes">
          <option v-for="n in 60" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("create_user.form_field_preferred_working_hours_minutes") }}
      </div>

      <div class="form-group" v-if="$store.state.user.role == 'admin'">
        <label for="register-role">{{ $t("create_user.form_field_role") }}: <span class="required">*</span></label> <br />
        <select v-model="user.role" class="custom-select my-1 mr-sm-2 w-25" id="register-role">
          <option v-for="role in roles" v-bind:value="role.name">
            {{ role.name }}
          </option>
        </select>
      </div>

      <div class="form-group text-right">
        <button class="btn btn-outline-secondary" @click="$router.go(-1)">{{ $t("create_user.button_cancel") }}</button>

        <button type="submit" class="btn btn-primary"
                @click="createUser()"
                @keyup.enter="createUser()"
                :disabled="buttonDisabled">
          <span v-if="buttonDisabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $t("create_user.button_create") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                showNotification: false,
                buttonDisabled: false,
                autoCheckForm: false,
                errors: [],
                serverErrors: [],
                user: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    preferred_working_hour_per_day: 0,
                    role: 'user'
                },
                roles: [],
                rules: {
                    user: {
                        name: ['required'],
                        email: ['required', 'email'],
                        password: ['required', 'min:6', 'confirmed'],
                        preferred_working_hour_per_day: ['required']
                    }
                }
            };
        },
        mounted() {
            this.loadRoles();
        },
        computed: {
            hours: {
                get: function () {
                    if(!this.user.preferred_working_hour_per_day) {
                        return 0;
                    }

                    return parseInt(parseInt(this.user.preferred_working_hour_per_day) / 60);
                },
                set: function (newValue) {
                    this.user.preferred_working_hour_per_day = parseInt(newValue) * 60 + this.minutes;
                }
            },
            minutes: {
                get: function () {
                    if(!this.user.preferred_working_hour_per_day) {
                        return 0;
                    }

                    return parseInt(this.user.preferred_working_hour_per_day) - this.hours * 60;
                },
                set: function (newValue) {
                    this.user.preferred_working_hour_per_day = this.hours * 60 + parseInt(newValue);
                }
            }
        },
        methods: {
            async createUser() {
                // activate compute to check form
                this.autoCheckForm = true;

                if(this.checkForm.length) {
                    return;
                }

                this.serverErrors = [];
                this.buttonDisabled = true;

                this.$Progress.start();
                this.$http.post('user', this.user)
                    .then(response => {
                        this.$Progress.finish();
                        this.showNotification = true;

                        setTimeout(() => this.$router.push('/users'), 1000);
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    });

            },
            async loadRoles() {
                if(this.$store.state.user.role != 'admin') {
                    return;
                }

                this.$Progress.start();
                this.$http.get('roles')
                    .then(response => {
                        this.roles = response.data.result;
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    })
            }
        }
    }
</script>
<style>

</style>
