import Vue from 'vue'
import App from './App'
import router from './router'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.min.css'
import api from "./services/api"
import mixin from "./services/mixin"
import store from "./services/store"
import VueProgressBar from 'vue-progressbar'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEdit, faTrash } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import VueI18n from 'vue-i18n'
import { languages } from './locales/index.js'
import { defaultLocale } from './locales/index.js'

Vue.config.productionTip = false;

// Awesome font
library.add(faEdit, faTrash);
Vue.component('font-awesome-icon', FontAwesomeIcon);

// Define Axios
Vue.prototype.$http = api;

// Init Porgress bar
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
});

// Mixin methods
Vue.mixin({
  methods: mixin,
});

// Init locales
Vue.use(VueI18n);
const i18n = new VueI18n({
  locale: defaultLocale,
  fallbackLocale: 'en',
  messages: Object.assign(languages)
});

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
