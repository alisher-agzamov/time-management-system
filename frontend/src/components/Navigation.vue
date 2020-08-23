<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <router-link class="navbar-brand mr-3" v-bind:to="'/'">{{ $t("navigation.page_title") }}</router-link>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item" v-for="item in links[this.$store.state.user.role].left">
            <router-link class="nav-link" v-bind:to="item.page">{{ $t(item.text) }}</router-link>
          </li>
        </ul>

        <ul class="navbar-nav mr-right">
          <li class="nav-item" v-for="item in links[this.$store.state.user.role].right">
            <router-link class="nav-link" v-bind:to="item.page">{{ $t(item.text) }}</router-link>
          </li>
        </ul>

      </div>
    </nav>
  </div>
</template>

<script>
    export default {
        name: 'Navigation',
        data() {
            return {
                links: {
                    guest: {
                        left: [],
                        right: [
                            {
                                text: 'navigation.login',
                                page:'/login'
                            },
                            {
                                text: 'navigation.signup',
                                page:'/signup'
                            }
                        ]
                    },
                    user: {
                        left: [
                            {
                                text: 'navigation.dashboard',
                                page:'/dashboard'
                            },
                            {
                                text: 'navigation.tasks',
                                page:'/tasks'
                            }
                        ],
                        right: [
                            {
                                text: 'navigation.profile',
                                page: '/profile'
                            },
                            {
                                text: 'navigation.logout',
                                page: '/logout'
                            }
                        ]
                    }
                }

            }
        }
    }
</script>
