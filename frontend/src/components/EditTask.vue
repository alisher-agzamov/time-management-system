<template>
  <div>
    <h1>{{ $t("edit_task.page_title") }}</h1>

    <div class="w-50 m-auto text-left">

      <div v-if="checkForm.length || serverErrors.length">
        <div class="alert alert-danger" role="alert" v-for="error in checkForm">{{ error }}</div>
        <div class="alert alert-danger" role="alert" v-for="error in serverErrors">{{ error }}</div>
      </div>

      <div class="alert alert-success" role="alert" v-if="showNotification">
        {{ $t("edit_task.notification_updated") }}
      </div>

      <div class="form-group">
        <label for="task-title">{{ $t("edit_task.form_field_title") }}: <span class="required">*</span></label>
        <input v-model="task.title" type="text" class="form-control" id="task-title" :placeholder="$t('edit_task.form_field_title_placeholder')">
      </div>

      <div class="form-group">
        <label for="task-description">{{ $t("edit_task.form_field_description") }}: <span class="required">*</span></label>
        <textarea v-model="task.description" class="form-control" id="task-description" :placeholder="$t('edit_task.form_field_description_placeholder')"></textarea>
      </div>

      <div class="form-group">
        <label for="task-date">{{ $t("edit_task.form_field_date") }}: <span class="required">*</span></label>


        <div class="col-md-4 ml-0 pl-0">
          <date-picker v-model="task.date" :config="options" id="task-date"></date-picker>
        </div>
      </div>

      <div class="form-group">
        <div>
          <label for="register-preferred-working-hour-per-day">{{ $t("edit_task.form_field_duration") }}: <span class="required">*</span></label>
        </div>
        <select v-model="hours" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day">
          <option v-for="n in 24" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("edit_task.form_field_date_hours") }}
        <select v-model="minutes" class="custom-select my-1 mr-sm-2 w-25" id="register-preferred-working-hour-per-day-minutes">
          <option v-for="n in 60" :value="n - 1">{{ n - 1 }}</option>
        </select>
        {{ $t("edit_task.form_field_date_minutes") }}
      </div>

      <div class="form-group text-right">
        <button class="btn btn-outline-secondary" @click="$router.go(-1)">{{ $t("edit_task.button_cancel") }}</button>

        <button type="submit" class="btn btn-primary"
                @click="updateTask()"
                @keyup.enter="updateTask()"
                :disabled="buttonDisabled">
          <span v-if="buttonDisabled" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>{{ $t("edit_task.button_update") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    // Import required dependencies
    import 'bootstrap/dist/css/bootstrap.css';

    // Import this component
    import datePicker from 'vue-bootstrap-datetimepicker';

    // Import date picker css
    import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';

    export default {
        data() {
            return {
                showNotification: false,
                buttonDisabled: false,
                autoCheckForm: false,
                errors: [],
                serverErrors: [],
                task: {
                    title: '',
                    description: '',
                    date: this.dateFormatter(new Date()),
                    duration: 0
                },
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                    maxDate: new Date()
                }
            };
        },
        created: function () {
            if(!['admin', 'user'].includes(this.$store.state.user.role)) {
                this.$router.push('/');
            }

            if(!this.$route.params.id) {
                this.$router.push('/tasks');
            }

            this.$store.state.page_title = '';
        },
        mounted() {
            this.$Progress.start();
            this.$http.get('tasks/' + this.$route.params.id)
                .then(response => {
                    this.$Progress.finish();
                    this.task = response.data.result;
                    this.$store.state.page_title = this.task.title;
                }, (response) => {
                    this.$Progress.fail()
                })
        },
        computed: {
            hours: {
                get: function () {
                    if(!this.task.duration) {
                        return 0;
                    }

                    return parseInt(parseInt(this.task.duration) / 60);
                },
                set: function (newValue) {
                    this.task.duration = parseInt(newValue) * 60 + this.minutes;
                }
            },
            minutes: {
                get: function () {
                    if(!this.task.duration) {
                        return 0;
                    }

                    return parseInt(this.task.duration) - this.hours * 60;
                },
                set: function (newValue) {
                    this.task.duration = this.hours * 60 + parseInt(newValue);
                }
            },
            checkForm: function (e) {

                this.errors = [];

                if(!this.autoCheckForm) {
                    return this.errors;
                }

                // Check name
                if (!this.task.title.trim()) {
                    this.errors.push(this.$t("edit_task.form_field_title_error"));
                }

                // Check description
                if (!this.task.description.trim()) {
                    this.errors.push(this.$t("edit_task.form_field_description_error"));
                }

                // Check date
                if (!this.task.date.trim()) {
                    this.errors.push(this.$t("edit_task.form_field_date_error"));
                }

                // Check duration
                if (!this.task.duration) {
                    this.errors.push(this.$t("edit_task.form_field_duration_error"));
                }

                return this.errors;
            }
        },
        methods: {
            async updateTask() {

                // activate compute to check form
                this.autoCheckForm = true;

                if(this.checkForm.length) {
                    return;
                }

                this.serverErrors = [];
                this.buttonDisabled = true;

                this.$Progress.start();
                this.$http.put('tasks/' + this.$route.params.id, this.task)
                    .then(response => {
                        this.$Progress.finish();
                        this.showNotification = true;

                        setTimeout(() => this.$router.go(-1), 1000);
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    });

            }
        },
        components: {
            datePicker
        }
    }
</script>
