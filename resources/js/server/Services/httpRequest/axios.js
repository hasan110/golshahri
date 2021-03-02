import axios from 'axios';

const Axios = axios.create({
    baseURL: 'http://localhost:8000/admin-area/'
});

export default Axios;