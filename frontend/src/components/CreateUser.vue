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

      <div class="form-group text-right">
        <router-link class="btn btn-outline-secondary" :to="{ name: 'Users'}">{{ $t("create_user.button_cancel") }}</router-link>

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
                    preferred_working_hour_per_day: 0
                }
            };
        },
        created: function () {
            if(!['admin', 'manager'].includes(this.$store.state.user.role)) {
                this.$router.push('/');
            }
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
            },
            checkForm: function (e) {

                this.errors = [];

                if(!this.autoCheckForm) {
                    return this.errors;
                }

                // Check name
                if (!this.user.name.trim()) {
                    this.errors.push(this.$t("create_user.form_field_name_error"));
                }

                // Check email
                if (!this.user.email) {
                    this.errors.push(this.$t("create_user.form_field_email_error"));
                }
                else if (!this.validEmail(this.user.email)) {
                    this.errors.push(this.$t("create_user.form_field_email_error_correct"));
                }

                // Check password
                if (!this.user.password.trim()) {
                    this.errors.push(this.$t("create_user.form_field_password_error"));
                }

                // Check password confirmation
                if (!this.user.password_confirmation.trim()) {
                    this.errors.push(this.$t("create_user.form_field_password_confirmation_error"));
                }
                else if (this.user.password != this.user.password_confirmation) {
                    this.errors.push(this.$t("create_user.form_field_password_confirmation_error"));
                }

                // Check preferred working hours
                if (!this.user.preferred_working_hour_per_day) {
                    this.errors.push(this.$t("create_user.form_field_preferred_working_hours_error"));
                }

                return this.errors;
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
                this.$http.post('auth/signup', this.user)
                    .then(response => {
                        this.$Progress.finish();
                        this.showNotification = true;

                        setTimeout(() => this.$router.push('/users'), 1000);
                    }, (response) => {
                        this.handleApiErrors(response.data);
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
