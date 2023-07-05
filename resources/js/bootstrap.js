import 'bootstrap';
import form from "./form.js";
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.onload = () => {
    form()
}
