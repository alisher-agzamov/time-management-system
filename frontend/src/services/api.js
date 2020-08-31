import axios from "axios";

const api = axios.create({
  baseURL: 'https://time-management-system-api.adnet.uz/api/v1/',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});

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


export default api;
