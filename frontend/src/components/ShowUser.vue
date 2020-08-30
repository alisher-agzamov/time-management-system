<template>
  <div>
    <h1>{{ $t("show_user.page_title") }}</h1>

    <div class="alert alert-success" role="alert" v-if="showNotification">
      {{ $t("show_user.notification_deleted") }}
    </div>

    <div class="text-left">
      <div>
        <strong>{{ $t("show_user.form_field_name") }}:</strong> {{user.name}}
      </div>
      <div>
        <strong>{{ $t("show_user.form_field_email") }}:</strong> {{user.email}}
      </div>
      <div>
        <strong>{{ $t("show_user.form_field_role") }}:</strong> {{user.role}}
      </div>
      <div>
        <strong>{{ $t("show_user.form_field_preferred_working_hours") }}:</strong> {{user.preferred_working_hour_per_day | asDuration}}
      </div>

      <div class="mt-3">
        <router-link
          v-if="$store.state.user.role == 'admin'"
          class="btn btn-info"
          :to="{ name: 'UserTasks', params: { id: this.$route.params.id }}">

          {{ $t("show_user.button_tasks") }}
        </router-link>

        <router-link
          class="btn btn-primary"
          :to="{ name: 'EditUser', params: { id: this.$route.params.id }}">
          <font-awesome-icon icon="edit" />
          {{ $t("show_user.button_edit") }}
        </router-link>

        <a class="btn btn-danger" href="javascript:;" v-on:click="deleteUser($route.params.id)">
          <font-awesome-icon icon="trash" />
          {{ $t("show_user.button_delete") }}
        </a>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
        created: function () {
            this.$store.state.page_title = '';
        },
        data() {
            return {
                showNotification: false,
                user: {}
            };
        },
        mounted() {
            this.loadUser();
        },
        methods: {
            loadUser: function() {
                this.$Progress.start();
                this.$http.get('user/' + this.$route.params.id)
                    .then(response => {
                        this.$Progress.finish();
                        this.user = response.data.result;
                        this.$store.state.page_title = this.user.name;
                    }, (response) => {
                        this.$Progress.fail()
                    });
            },
            deleteUser: function(userId) {
                if(!confirm(this.$t("show_user.deletion_confirm"))){
                    return;
                }

                this.$http.delete('user/' + userId)
                    .then(response => {
                        this.$Progress.finish();

                        this.showNotification = true;
                        setTimeout(() => this.$router.push('/users'), 1000);
                    }, (response) => {
                        this.$Progress.fail()
                    });
            }
        }
    }
</script>
<style>

</style>
