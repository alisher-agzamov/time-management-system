<template>
  <div>
    <h1>{{ $t("show_task.page_title") }}</h1>

    <div class="alert alert-success" role="alert" v-if="showNotification">
      {{ $t("show_task.notification_deleted") }}
    </div>

    <div class="text-left">
      <div>
        <strong>{{ $t("show_task.form_field_date") }}:</strong> {{task.date}}
      </div>
      <div>
        <strong>{{ $t("show_task.form_field_title") }}:</strong> {{task.title}}
      </div>
      <div>
        <strong>{{ $t("show_task.form_field_description") }}:</strong> {{task.description}}
      </div>
      <div>
        <strong>{{ $t("show_task.form_field_duration") }}:</strong> {{task.duration}}
      </div>

      <div class="mt-3">
        <router-link  v-if="!isAdminAction"
          class="btn btn-primary"
          :to="{ name: 'EditTask', params: { id: this.$route.params.id }}">
          <font-awesome-icon icon="edit" />
          {{ $t("show_task.button_edit") }}
        </router-link>

        <router-link v-else
          class="btn btn-primary"
           :to="{ name: 'EditUserTask', params: { id: this.$route.params.id, user_id: this.$route.params.user_id }}">
          <font-awesome-icon icon="edit" />
          {{ $t("show_task.button_edit") }}
        </router-link>

        <a class="btn btn-danger" href="javascript:;" v-on:click="deleteTask($route.params.id)">
          <font-awesome-icon icon="trash" />
          {{ $t("show_task.button_delete") }}
        </a>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
        created: function () {
            if(!['admin', 'user'].includes(this.$store.state.user.role)) {
                this.$router.push('/');
            }

            if(!this.$route.params.id) {
                this.$router.push('/tasks');
            }

            this.$store.state.page_title = '';
        },
        data() {
            return {
                isAdminAction: false, // is the current action doing under admin
                showNotification: false,
                task: {}
            };
        },
        mounted() {
            this.loadTask();

            if(this.$route.params.user_id) {
                this.isAdminAction = true;
            }
        },
        methods: {
            loadTask: function() {
                this.$Progress.start();
                this.$http.get('tasks/' + this.$route.params.id)
                    .then(response => {
                        this.$Progress.finish();
                        this.task = response.data.result;
                        this.$store.state.page_title = this.task.title;
                    }, (response) => {
                        this.$Progress.fail()
                    });
            },
            deleteTask: function(taskId) {
                if(!confirm(this.$t("show_task.deletion_confirm"))){
                    return;
                }

                this.$http.delete('tasks/' + taskId)
                    .then(response => {
                        this.$Progress.finish();

                        this.showNotification = true;
                        setTimeout(() => this.$router.go(-1), 1000);
                    }, (response) => {
                        this.$Progress.fail()
                    });
            },
        }
    }
</script>
<style>

</style>
