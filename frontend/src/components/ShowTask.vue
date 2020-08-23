<template>
  <div>
    <h1>{{ $t("show_task.page_title") }}</h1>

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

      <div>
        <button>{{ $t("show_task.button_edit") }}</button>
        <button>{{ $t("show_task.button_delete") }}</button>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
        created: function () {
            if(!this.$store.state.isAuthenticated
                || !this.$route.params.id) {
                this.$router.push('/tasks');
            }
        },
        data() {
            return {
                task: {}
            };
        },
        mounted() {
            this.$Progress.start();
            this.$http.get('tasks/' + this.$route.params.id)
                .then(response => {
                    this.$Progress.finish();
                    this.task = response.data.result;
                }, (response) => {
                    this.$Progress.fail()
                })
        }
    }
</script>
<style>

</style>
