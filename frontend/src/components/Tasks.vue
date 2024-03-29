<template>
  <div>
    <h1 v-if="!isAdminAction">{{ $t("tasks.page_title") }}</h1>
    <h1 v-else>{{ $t("tasks.page_title_user") }}</h1>

    <div class="alert alert-success" role="alert" v-if="showNotification">
      {{ $t("tasks.notification_deleted") }}
    </div>

    <div class="row mb-3">
      <div class="col-sm text-left">
        <router-link v-if="!isAdminAction" class="btn btn-primary" :to="{ name: 'CreateTask'}">{{ $t("tasks.create") }}</router-link>
        <router-link v-else class="btn btn-primary" :to="{ name: 'UserTasksCreate'}">{{ $t("tasks.create_for_user") }}</router-link>
      </div>

      <div class="col-sm">
        <label for="date-filter">{{ $t("tasks.date_filter") }}</label>
        <date-range-picker
          id="date-filter"
          ref="picker"
          :opens="datePicker.opens"
          :locale-data="{ firstDay: 1, format: 'YYYY-MM-DD' }"
          :minDate="datePicker.minDate" :maxDate="datePicker.maxDate"
          :singleDatePicker="datePicker.singleDatePicker"
          :timePicker="datePicker.timePicker"
          :timePicker24Hour="datePicker.timePicker24Hour"
          :showWeekNumbers="datePicker.showWeekNumbers"
          :showDropdowns="datePicker.showDropdowns"
          :autoApply="datePicker.autoApply"
          v-model="filter.dateRange"
          @update="updateValues"
          :linkedCalendars="filter.linkedCalendars">
          <template v-slot:input="picker" style="min-width: 350px;">
            {{ filter.startDate }} - {{ filter.endDate }}
          </template>
        </date-range-picker>
      </div>

      <div class="col-sm text-right">
        <button class="btn btn-secondary" v-on:click="exportTasks">{{ $t("tasks.export") }}</button>
      </div>
    </div>


    <div class="alert alert-secondary mt-5" role="alert" v-if="tasksNotFoundNotification">
      {{ $t("tasks.notification_not_found") }}
    </div>

    <table class="table text-left" v-if="!tasksNotFoundNotification">
      <thead class="">
      <tr>
        <th scope="col">{{ $t("tasks.title") }}</th>
        <th scope="col" style="width: 50px;">{{ $t("tasks.duration") }}</th>
        <th scope="col" style="width: 50px;">{{ $t("tasks.action") }}</th>
      </tr>
      </thead>
      <template v-for="(items, index) in tasks.tasks">
        <thead class="thead-light">
        <tr>
          <th scope="col">{{index}}</th>
          <th scope="col" colspan="2">{{items.total_duration | asDuration}}</th>
        </tr>
        </thead>

        <tr v-for="item in items.tasks">
          <td>
            <router-link v-if="!isAdminAction" :class="renderItemClass(items.covered_day_hours)" :to="{ name: 'ShowTask', params: { id: item.id }}">{{ item.title }}</router-link>
            <router-link v-else :class="renderItemClass(items.covered_day_hours)" :to="{ name: 'ShowUserTask', params: { id: item.id, user_id: $route.params.id }}">{{ item.title }}</router-link>
          </td>
          <td>{{item.duration | asDuration}}</td>
          <td>
            <router-link
              :to="{ name: 'EditTask', params: { id: item.id }}">
              <font-awesome-icon icon="edit" />
            </router-link>

            <a class="item-red" href="javascript:;" v-on:click="deleteTask(item.id)">
              <font-awesome-icon icon="trash" />
            </a>
          </td>
        </tr>
      </template>

    </table>
  </div>
</template>

<script>
    import DateRangePicker from 'vue2-daterange-picker'
    import 'vue2-daterange-picker/dist/vue2-daterange-picker.css'

    export default {
        data() {
            return {
                isAdminAction: false, // is the current action doing under admin
                datePicker: {
                    opens: 'center',
                    minDate: '2020-01-01',
                    maxDate: this.dateFormatter(new Date()),
                    singleDatePicker: false,
                    timePicker: false,
                    timePicker24Hour: true,
                    showWeekNumbers: false,
                    showDropdowns: false,
                    autoApply: true,
                    linkedCalendars: true,
                },
                date: new Date(),
                showNotification: false,
                tasksNotFoundNotification: false,
                tasks: [],
                user: this.$store.state.user,
                filter: {
                    dateRange: {},
                    startDate: null,
                    endDate: this.dateFormatter(new Date()),
                },
                options: {
                    format: 'YYYY-MM-DD',
                    useCurrent: false,
                    maxDate: new Date()
                }
            };
        },
        mounted() {
            // Set start date in filter as current - 30 days
            let startDate = new Date();
            startDate.setDate(startDate.getDate() - 30);
            this.filter.startDate = this.dateFormatter(startDate);

            if(this.$route.params.id) {
                this.isAdminAction = true;
                this.user = {
                    id: this.$route.params.id
                };
            }

            this.loadTasks();
        },
        methods: {
            loadTasks: function() {
                this.$Progress.start();

                let params = {
                    date_from: this.filter.startDate,
                    date_to: this.filter.endDate
                };

                if(this.isAdminAction) {
                    params.user_id = this.user.id;
                }

                this.$http.get('tasks', { params: params })
                    .then(response => {
                        this.$Progress.finish();
                        this.tasks = response.data.result;
                        this.tasksNotFoundNotification = !Object.keys(this.tasks.tasks).length;
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    })
            },
            updateValues: function(dates) {
                this.filter.startDate = this.dateFormatter(dates.startDate);
                this.filter.endDate = this.dateFormatter(dates.endDate);

                this.loadTasks();
            },
            renderItemClass: function(coveredDayHours) {
                if(coveredDayHours) {
                    return 'item-green';
                }

                return 'item-red';
            },
            deleteTask: function(taskId) {
                if(!confirm(this.$t("tasks.deletion_confirm"))){
                    return;
                }

                this.$http.delete('tasks/' + taskId)
                    .then(response => {
                        this.$Progress.finish();

                        this.showNotification = true;
                        setTimeout(() => this.showNotification = false, 2000);

                        this.loadTasks();
                    }, (response) => {
                        this.$Progress.fail()
                    });
            },
            exportTasks: function() {

                let params = {
                    date_from: this.filter.startDate,
                    date_to: this.filter.endDate
                };

                if(this.isAdminAction) {
                    params.user_id = this.user.id;
                }

                this.$http({
                    url: 'tasks/export',
                    params: params,
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'tasks-export-' + this.filter.startDate + '-' + this.filter.endDate + '.html');
                    document.body.appendChild(fileLink);

                    fileLink.click();
                });
            }
        },
        components: {
            DateRangePicker
        }
    }
</script>
<style>
  .item-green, .item-green:hover {
    color: #42b983;
  }
  .item-red, .item-red:hover {
    color: firebrick;
  }
</style>
