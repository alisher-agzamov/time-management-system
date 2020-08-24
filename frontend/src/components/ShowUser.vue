<template>
  <div>
    <h1>{{ $t("show_user.page_title") }}</h1>

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
        <strong>{{ $t("show_user.form_field_preferred_working_hours") }}:</strong> {{user.preferred_working_hour_per_day}}
      </div>

      <div>
        <button>{{ $t("show_user.button_edit") }}</button>
        <button>{{ $t("show_user.button_delete") }}</button>
      </div>
    </div>

  </div>
</template>

<script>
    export default {
        created: function () {
            if(!['admin', 'manager'].includes(this.$store.state.user.role)) {
                this.$router.push('/');
            }

            if(!this.$route.params.id) {
                this.$router.push('/users');
            }
        },
        data() {
            return {
                user: {}
            };
        },
        mounted() {
            this.$Progress.start();
            this.$http.get('user/' + this.$route.params.id)
                .then(response => {
                    this.$Progress.finish();
                    this.user = response.data.result;
                }, (response) => {
                    this.$Progress.fail()
                })
        }
    }
</script>
<style>

</style>
