// import Alpine from "alpinejs";
import "./bootstrap";
// window.Alpine = Alpine;
// Alpine.start();
// Simple notification refresh function

window.refreshNotifications = function () {
    window.dispatchEvent(new CustomEvent("refresh-notifications"));
};

// Auto refresh notifications every 30 seconds (optional)
if (document.querySelector("[wire\\:id]")) {
    setInterval(() => {
        // Only refresh if user is authenticated and on a page with Livewire components
        if (document.querySelector('meta[name="csrf-token"]')) {
            window.refreshNotifications();
        }
    }, 30000); // 30 seconds
}
