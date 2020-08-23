// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuex from 'vuex'
import App from './App'
import router from './router'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import api from "./services/api"
import VueProgressBar from 'vue-progressbar'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import VueConfirmDialog from 'vue-confirm-dialog'
import VueI18n from 'vue-i18n'
import { languages } from './locales/index.js'
import { defaultLocale } from './locales/index.js'

// Awesome font
library.add(faEdit, faTrash);
Vue.component('font-awesome-icon', FontAwesomeIcon);

// Confirm dialogs
Vue.use(VueConfirmDialog)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)


Vue.config.productionTip = false;

// Vuex
Vue.use(Vuex);

// Use locales
Vue.use(VueI18n);
const messages = Object.assign(languages);

// Vuex storage
const store = new Vuex.Store({
  state: {
    token: {
      access: null,
      expires_at: null,
      type: "Bearer"
    },
    user: {
      name: null,
      email: null,
      role: 'guest',
      preferred_working_hour_per_day: 0
    },
    isAuthenticated: false,
    count: 0
  },
  mutations: {
    // Extract data from the local storage
    initialiseStore(state) {
      if (localStorage.getItem('isAuthenticated')) {
        state.isAuthenticated = localStorage.getItem('isAuthenticated');

        state.token.access = localStorage.getItem('token_access');
        state.token.expires_at = localStorage.getItem('token_expires_at');
        state.token.type = localStorage.getItem('token_type');

        state.user.role = localStorage.getItem('user_role');
        state.user.preferred_working_hour_per_day = localStorage.getItem('user_preferred_working_hour_per_day');
      }
    },
    // Set data in local storage
    syncLocalStorage(state) {
      if(state.isAuthenticated) {
        localStorage.setItem('isAuthenticated', state.isAuthenticated);

        localStorage.setItem('token_access', state.token.access);
        localStorage.setItem('token_expires_at', state.token.expires_at);
        localStorage.setItem('token_type', state.token.type);

        localStorage.setItem('user_role', state.user.role);
        localStorage.setItem('user_preferred_working_hour_per_day', state.user.preferred_working_hour_per_day);
      }
      else {
        localStorage.removeItem('isAuthenticated');
        localStorage.removeItem('token_access');
        localStorage.removeItem('token_expires_at');
        localStorage.removeItem('token_type');
        localStorage.removeItem('user_role');
        localStorage.removeItem('user_preferred_working_hour_per_day');
      }

    }
  }
});


// Define Axios
Vue.prototype.$http = api;
api.defaults.timeout = 100000;

api.interceptors.request.use(
  config => {

    // Use Authorization in headers
    if (app.$store.state.isAuthenticated) {
      config.headers.common["Authorization"] = app.$store.state.token.type + ' ' + app.$store.state.token.access;
    }

    return config;
  },
  error => {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  response => {
    return Promise.resolve(response);
  },
  error => {
    if (error.response.status) {
      return Promise.reject(error.response);
    }
  }
);

// Init Porgress bar
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
});

// Mixin methods
Vue.mixin({
  methods: {
    dateFormatter: function (date) {
      return date.toJSON().slice(0,10);
    },
    handleApiErrors: function(data) {

      this.$Progress.fail();
      this.buttonDisabled = false;

      // Display form field errors
      if(data.errors && data.errors) {
        for (let i in data.errors) {
          this.serverErrors = this.serverErrors.concat(data.errors[i]);
        }
      }
      // Display error message
      else if(data.error) {
        this.serverErrors.push(data.error);
      }
    }
  },
});

// Create VueI18n instance with options
var i18n = new VueI18n({
  locale: defaultLocale,
  fallbackLocale: 'de',
  messages
})


/* eslint-disable no-new */
window.app = new Vue({
  el: '#app',
  router,
  template: '<App/>',
  components: { App },
  store: store,
  i18n,
  beforeCreate() {
    this.$store.commit('initialiseStore');
  }
});
