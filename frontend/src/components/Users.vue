<template>
  <div>
    <h1>{{ $t("users.page_title") }}</h1>

    <div class="alert alert-success" role="alert" v-if="showNotification">
      {{ $t("users.notification_deleted") }}
    </div>

    <div class="row mb-3">
      <div class="col-sm text-left">
        <router-link class="btn btn-primary" :to="{ name: 'CreateUser'}">{{ $t("users.create") }}</router-link>
      </div>
    </div>


    <div class="alert alert-secondary mt-5" role="alert" v-if="notFoundNotification">
      {{ $t("users.notification_not_found") }}
    </div>

    <table class="table text-left" v-if="!notFoundNotification">
      <thead class="">
      <tr>
        <th scope="col">{{ $t("users.name") }}</th>
        <th scope="col">{{ $t("users.email") }}</th>
        <th scope="col">{{ $t("users.role") }}</th>
        <th scope="col" style="width: 50px;">{{ $t("users.action") }}</th>
      </tr>
      </thead>
      <tr v-for="item in users">
        <td>
          <router-link :to="{ name: 'ShowTask', params: { id: item.id }}">{{ item.name }}</router-link>
        </td>
        <td>{{ item.email }}</td>
        <td>{{ item.role }}</td>
        <td>
          <router-link
            :to="{ name: 'EditUser', params: { id: item.id }}">
            <font-awesome-icon icon="edit" />
          </router-link>

          <a class="item-red" href="javascript:;" v-on:click="deleteUser(item.id)">
            <font-awesome-icon icon="trash" />
          </a>
        </td>
      </tr>
    </table>
  </div>
</template>

<script>
    export default {
        created: function () {
            if(!['admin', 'manager'].includes(this.$store.state.user.role)) {
                this.$router.push('/');
            }
        },
        data() {
            return {
                showNotification: false,
                notFoundNotification: false,
                users: [],
                user: this.$store.state.user
            };
        },
        mounted() {
            this.loadUsers();
        },
        methods: {
            loadUsers: function() {
                this.$Progress.start();
                this.$http.get('user')
                    .then(response => {
                        this.$Progress.finish();
                        this.users = response.data.result;
                        this.notFoundNotification = !Object.keys(this.users).length;
                    }, (response) => {
                        this.handleApiErrors(response.data);
                    })
            },
            deleteUser: function(userId) {
                if(!confirm(this.$t("users.deletion_confirm"))){
                    return;
                }

                this.$http.delete('user/' + userId)
                    .then(response => {
                        this.$Progress.finish();

                        this.showNotification = true;
                        setTimeout(() => this.showNotification = false, 2000);

                        this.loadUsers();
                    }, (response) => {
                        this.$Progress.fail()
                    });
            }
        }
    }
</script>
<style>
  .item-red, .item-red:hover {
    color: firebrick;
  }
</style>
