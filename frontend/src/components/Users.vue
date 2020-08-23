<template>
  <div>
    <h1>{{ $t("users.page_title") }}</h1>
  </div>
</template>

<script>
    export default {
        created: function () {
            if(!this.$store.state.isAuthenticated) {
                this.$router.push('/');
            }
        }
    }
</script>
<style>

</style>
