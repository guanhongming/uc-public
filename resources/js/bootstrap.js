import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.defaults.withCredentials = true;
axios.defaults.baseURL = import.meta.env.VITE_APP_URL;
window.axios.interceptors.request.use((config) => {
    const isStaging = window.location.pathname.startsWith('/staging');
    if (isStaging && config.url.startsWith('/api')) {
        config.url = '/staging' + config.url;
        console.log('HELLO /staging');
    }

    return config;
});
