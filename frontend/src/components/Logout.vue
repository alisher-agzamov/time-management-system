<template>
  <div>
    <p>{{ $t("logout.please_wait") }}</p>
  </div>
</template>

<script>
    export default {
        created: function () {
            if(!this.$store.state.isAuthenticated) {
                this.$router.push('/');
            }

            this.$Progress.start();
            this.$http.get('auth/logout').then(response => {
                this.$Progress.finish();
                this.eraseData();
            }, (response) => {
                this.$Progress.fail();
                this.eraseData();
            });
        },
        methods: {
          eraseData()
          {
              this.$store.state.isAuthenticated = false;
              this.$store.state.token.access = null;
              this.$store.state.token.expires_at = null;
              this.$store.state.user.role = 'guest';

              this.$store.commit('syncLocalStorage');

              this.$router.push('/');
          }
        }
    }
</script>
<style>

</style>
