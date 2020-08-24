import Vuex from 'vuex'
import Vue from 'vue'

Vue.use(Vuex);

export default new Vuex.Store({
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
    page_title: null
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
