import axios from 'axios';

function login(email, password) {
    axios.get('/sanctum/csrf-cookie').then(() => {
        axios.post('/login', {
            email: email,
            password: password
        })
        .then(response => {
            console.log('Login successful', response.data);
        })
        .catch(error => {
            console.error('Login failed', error.response.data);
        });
    });
}

