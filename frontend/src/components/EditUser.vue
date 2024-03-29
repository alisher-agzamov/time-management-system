<template>
  <div>
    <h1>{{ $t("edit_user.page_title") }}</h1>

    <div class="w-50 m-auto text-left">

      <div v-if="checkForm.length || serverErrors.length">
        <div class="alert alert-danger" role="alert" v-for="error in checkForm">{{ error }}</div>
        <div class="alert alert-danger" role="alert" v-for="error in serverErrors">{{ error }}</div>
      </div>

      <div class="alert alert-success" role="alert" v-if="profileUpdated">
        {{ $t("edit_user.notification_updated") }}
      </div>

      <div class="form-group">
        <label for="register-name">{{ $t("edit_user.form_field_name") }}: <span class="required">*</span></label>
        <input v-model="user.name" type="text" class="form-control" id="register-name" :placeholder="$t('edit_user.form_field_name_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-email">{{ $t("edit_user.form_field_email") }}: <span class="required">*</span></label>
        <input v-model="user.email" type="email" class="form-control" id="register-email" aria-describedby="emailHelp" :placeholder="$t('edit_user.form_field_email_placeholder')">
      </div>

      <div class="form-group">
        <label for="register-preferred-working-hour-per-day">{{ $t("edit_user.form_field_preferred_working_hours") }}: <span class="required">*</span></label> <br />
        <select v-model="hours" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day">
          <option v-for="n in 24" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("edit_user.form_field_preferred_working_hours_hours") }}
        <select v-model="minutes" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day-minutes">
          <option v-for="n in 60" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("edit_user.form_field_preferred_working_hours_minutes") }}
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
        <button class="btn btn-outline-secondary" @click="$router.go(-1)">{{ $t("edit_user.button_cancel") }}</button>

        <button type="submit" class="btn btn-primary"
                @click="updateUserProfile()"
                @keyup.enter="updateUserProfile()"
                :disabled="buttonDisabled">
          <span v-if="buttonDisabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $t("edit_user.button_update") }}</button>
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
                user: {
                    name: '',
                    email: '',
                    preferred_working_hour_per_day: 0
                },
                roles: [],
                rules: {
                    user: {
                        name: ['required'],
                        email: ['required', 'email'],
                        preferred_working_hour_per_day: ['required']
                    }
                }
            };
        },
        created: function () {
            this.$store.state.page_title = '';
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
            async updateUserProfile() {
                // activate compute to check form
                this.autoCheckForm = true;

                if(this.checkForm.length) {
                    return;
                }

                this.serverErrors = [];
                this.buttonDisabled = true;

                this.$Progress.start();

                let data = this.user;

                // Delete role if the user does not have admin role
                if(this.$store.state.user.role != 'admin') {
                    this.$delete(data, 'role');
                }

                this.$http.put('user/' + this.$route.params.id, data)
                    .then(response => {
                        this.$Progress.finish();
                        this.profileUpdated = true;
                        this.buttonDisabled = false;

                        setTimeout(() => this.$router.go(-1), 1000);
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    });
            },
            async loadUser() {
                this.$Progress.start();
                this.$http.get('user/' + this.$route.params.id)
                    .then(response => {
                        this.$Progress.finish();
                        this.user = response.data.result;
                        this.$store.state.page_title = this.user.name;

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
            },
            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        },
        mounted() {
            this.loadUser();
            this.loadRoles();
        }
    }
</script>
<style>

</style>
