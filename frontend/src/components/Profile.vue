<template>
  <div>
    <h1>{{ $t("profile.page_title") }}</h1>

    <div class="w-50 m-auto text-left">

      <div v-if="checkForm.length || serverErrors.length">
        <div class="alert alert-danger" role="alert" v-for="error in checkForm">{{ error }}</div>
        <div class="alert alert-danger" role="alert" v-for="error in serverErrors">{{ error }}</div>
      </div>

      <div class="alert alert-success" role="alert" v-if="profileUpdated">
        {{ $t("profile.notification_updated") }}
      </div>

      <div class="form-group">
        <label for="register-name">{{ $t("profile.form_field_name") }}: <span class="required">*</span></label>
        <input v-model="user.name" type="text" class="form-control" id="register-name" :placeholder="$t('profile.form_field_name_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-email">{{ $t("profile.form_field_email") }}: <span class="required">*</span></label>
        <input v-model="user.email" type="email" class="form-control" id="register-email" aria-describedby="emailHelp" :placeholder="$t('profile.form_field_email_placeholder')" disabled>
      </div>

      <div class="form-group">
        <label for="register-preferred-working-hour-per-day">{{ $t("profile.form_field_preferred_working_hours") }}: <span class="required">*</span></label> <br />
        <select v-model="hours" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day">
          <option v-for="n in 24" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("profile.form_field_preferred_working_hours_hours") }}
        <select v-model="minutes" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day-minutes">
          <option v-for="n in 60" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("profile.form_field_preferred_working_hours_minutes") }}
      </div>

      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary"
                @click="updateUserProfile()"
                @keyup.enter="updateUserProfile()"
                :disabled="buttonDisabled">
          <span v-if="buttonDisabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $t("profile.button_update") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                profileUpdated: false,
                buttonDisabled: false,
                autoCheckForm: false,
                errors: [],
                serverErrors: [],
                user: this.$store.state.user
            };
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
                    this.errors.push(this.$t("profile.form_field_name_error"));
                }

                // Check preferred working hours
                if (!this.user.preferred_working_hour_per_day) {
                    this.errors.push(this.$t("profile.form_field_preferred_working_hours_error"));
                }

                return this.errors;
            }
        },
        methods: {
            async updateUserProfile() {
                // activate compute to check form
                this.autoCheckForm = true;

                if(this.checkForm.length) {
                    return;
                }

                this.serverErrors = [];
                this.buttonDisabled = true;

                this.$Progress.start();
                this.$http.put('user/me', {
                    name: this.user.name,
                    preferred_working_hour_per_day: this.user.preferred_working_hour_per_day,
                })
                    .then(response => {
                        this.$Progress.finish();
                        this.profileUpdated = true;
                        this.buttonDisabled = false;

                        setTimeout(() => this.profileUpdated = false, 2000);
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    });
            },
            async getUser() {
                this.$Progress.start();
                this.$http.get('user/me')
                    .then(response => {
                        this.$Progress.finish();
                        this.user.name = response.data.result.name;
                        this.user.email = response.data.result.email;
                        this.user.preferred_working_hour_per_day = response.data.result.preferred_working_hour_per_day;

                        // Update current state
                        this.$store.state.user.name = response.data.result.name;
                        this.$store.state.user.email = response.data.result.email;
                        this.$store.state.user.role = response.data.result.role;
                        this.$store.state.user.preferred_working_hour_per_day = response.data.result.preferred_working_hour_per_day;

                        this.$store.commit('syncLocalStorage');

                    }, (response) => {
                        this.handleApiErrors(response.data);
                    })
            },
            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        },
        mounted() {
            this.getUser();
        }
    }
</script>
<style>

</style>
