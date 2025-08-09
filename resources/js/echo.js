import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
    auth: {
        headers: {
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    },
});

// Debug untuk melihat koneksi Pusher
if (import.meta.env.VITE_APP_DEBUG) {
    window.Echo.connector.pusher.connection.bind("connected", function () {
        console.log("Pusher connected successfully");
    });

    window.Echo.connector.pusher.connection.bind("disconnected", function () {
        console.log("Pusher disconnected");
    });

    window.Echo.connector.pusher.connection.bind("error", function (err) {
        console.error("Pusher connection error:", err);
    });
}

// Global error handler untuk debugging
window.addEventListener("error", function (e) {
    if (e.message.includes("Echo") || e.message.includes("Pusher")) {
        console.error("Echo/Pusher Error:", e);
    }
});

// Test connection pada page load (hanya untuk debugging)
document.addEventListener("DOMContentLoaded", function () {
    if (window.Echo && import.meta.env.VITE_APP_DEBUG) {
        console.log("Echo instance:", window.Echo);
        console.log(
            "Pusher state:",
            window.Echo.connector.pusher.connection.state
        );
    }
});
