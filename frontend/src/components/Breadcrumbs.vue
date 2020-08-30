<template>
  <div class="mt-0">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <template v-for="item in list">
          <li class="breadcrumb-item" v-if="item.page"><router-link :to="{ name: item.page, params: item.params}">{{ renderPageTitle(item.name) }}</router-link></li>
          <li class="breadcrumb-item active" aria-current="page" v-else>{{ renderPageTitle(item.name) }}</li>
        </template>
      </ol>
    </nav>
  </div>
</template>

<script>
    export default {
        data() {
            return {
              list: [],
              user: {
                  name: null
              }
            }
        },
        created: function() {
            this.generate();
        },
        watch: {
            $route (to, from){
                this.generate();
            }
        },
        computed: {

        },
        methods: {
            generate: function() {
                this.list = [];

                this.addToList('Home', 'Index');

                switch (this.$route.name) {
                    case'Index':
                    case'Home':
                      this.list = [];
                      this.addToList(this.$t("navigation.home"), null, {});
                      break;

                    case'Login':
                      this.addToList(this.$t("navigation.login"), null, {});
                      break;

                    case'Signup':
                      this.addToList(this.$t("navigation.signup"), null, {});
                      break;

                    case'Profile':
                      this.addToList(this.$t("navigation.profile"), null, {});
                      break;

                    case'Tasks':
                        this.addToList(this.$t("navigation.tasks"), null, {});
                        break;

                    case'CreateTask':
                        this.addToList(this.$t("navigation.tasks"), 'Tasks', {});
                        this.addToList(this.$t("navigation.create"), null, {});
                        break;

                    case'ShowTask':
                        this.addToList(this.$t("navigation.tasks"), 'Tasks', {});
                        this.addToList('page_title', null, {});
                        break;

                    case'EditTask':
                        this.addToList(this.$t("navigation.tasks"), 'Tasks', {});
                        this.addToList('page_title', 'ShowTask', {});
                        this.addToList(this.$t("navigation.edit"), null, {});
                        break;

                    case'Users':
                        this.addToList(this.$t("navigation.users"), null, {});
                        break;

                    case'CreateUser':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList(this.$t("navigation.create"), null, {});
                        break;

                    case'ShowUser':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('page_title', null, {});
                        break;

                    case'EditUser':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('page_title', 'ShowUser', {});
                        this.addToList(this.$t("navigation.edit"), null, {});
                        break;

                    case'UserTasks':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('user_name', 'ShowUser', {});
                        this.addToList(this.$t("navigation.user_tasks"), null, {});

                        this.loadUser(this.$route.params.id);
                        break;

                    case'UserTasksCreate':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('user_name', 'ShowUser', {});
                        this.addToList(this.$t("navigation.user_tasks"), 'UserTasks', {});
                        this.addToList(this.$t("navigation.create"), null, {});

                        this.loadUser(this.$route.params.id);
                        break;

                    case'ShowUserTask':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('user_name', 'ShowUser', {
                            id: this.$route.params.user_id
                        });
                        this.addToList(this.$t("navigation.user_tasks"), 'UserTasks', {
                            id: this.$route.params.user_id
                        });
                        this.addToList('page_title', null, {});

                        this.loadUser(this.$route.params.user_id);
                        break;

                    case'EditUserTask':
                        this.addToList(this.$t("navigation.users"), 'Users', {});
                        this.addToList('user_name', 'ShowUser', {
                            id: this.$route.params.user_id
                        });
                        this.addToList(this.$t("navigation.user_tasks"), 'UserTasks', {
                            id: this.$route.params.user_id
                        });

                        this.addToList('page_title', 'ShowUserTask', {
                            user_id: this.$route.params.user_id,
                            id: this.$route.params.id
                        });

                        this.addToList(this.$t("navigation.edit"), null, {});

                        this.loadUser(this.$route.params.user_id);
                        break;
                }
            },
            addToList: function(name, page, params) {
                this.list.push({
                    name: name,
                    page: page,
                    params: params
                });
            },
            renderPageTitle: function(title) {
                if(title == 'page_title') {
                    return this.$store.state.page_title;
                }
                else if(title == 'user_name'
                    && this.$route.params.id) {
                    return this.user.name;
                }
                else {
                    return title;
                }
            },
            loadUser: function(user_id) {
                this.$http.get('user/' + user_id)
                    .then(response => {
                        this.user = response.data.result;
                    });
            }
        }
    }
</script>
