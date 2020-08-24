<template>
  <div>
    <h1>{{ $t("index.page_title") }}</h1>

  </div>
</template>

<script>
  export default {
    data () {
      return {

      }
    },
      created: function () {
          if(!this.$store.state.isAuthenticated) {
              this.$router.push('/login');
          }
      },
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
