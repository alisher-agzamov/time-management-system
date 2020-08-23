import axios from "axios";

export default axios.create({
  baseURL: 'http://laravel.loc/api/v1/',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json'
  }
});
