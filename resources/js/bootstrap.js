import axios from 'axios';
window.axios = axios;

window.axios.defaults.withCredentials = true; // クッキーを有効化
// window.axios.defaults.baseURL = 'http://localhost'; // LaravelのURLを設定
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
