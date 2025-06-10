import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/', // Your API base URL
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
    },
});

export default apiClient; 