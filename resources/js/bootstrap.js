import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */
import "./echo";


// Cek koneksi Pusher
window.Echo.connector.pusher.connection.state

// Listen manual untuk testing
window.Echo.private('class.1') // ganti 1 dengan class_id yang sesuai
    .listen('.material.created', (e) => {
        console.log('Received notification:', e);
    });
