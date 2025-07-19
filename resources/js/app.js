import Alpine from "alpinejs";
import "./bootstrap";
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", function () {
    const logoutModal = document.getElementById("logoutModal");
    const logoutTrigger = document.getElementById("logoutTrigger"); // Tombol logout desktop
    const logoutTriggerMobile = document.getElementById("logoutTriggerMobile"); // Tombol logout mobile
    const cancelLogoutButton = document.getElementById("cancelLogoutButton");

    // Fungsi untuk menampilkan modal
    function showModal() {
        if (logoutModal) {
            logoutModal.classList.remove("hidden");
        }
    }

    // Fungsi untuk menyembunyikan modal
    function hideModal() {
        if (logoutModal) {
            logoutModal.classList.add("hidden");
        }
    }

    // Event listener untuk tombol logout (desktop)
    if (logoutTrigger) {
        logoutTrigger.addEventListener("click", showModal);
    }

    // Event listener untuk tombol logout (mobile)
    if (logoutTriggerMobile) {
        logoutTriggerMobile.addEventListener("click", showModal);
    }

    // Event listener untuk tombol "Batal" di dalam modal
    if (cancelLogoutButton) {
        cancelLogoutButton.addEventListener("click", hideModal);
    }

    // Menutup modal saat mengklik di luar area modal (overlay)
    if (logoutModal) {
        logoutModal.addEventListener("click", function (event) {
            // Pastikan klik terjadi pada overlay, bukan pada modal content
            if (
                event.target === logoutModal ||
                event.target.classList.contains("bg-opacity-75")
            ) {
                hideModal();
            }
        });
    }

    // Menutup modal saat menekan tombol ESC
    document.addEventListener("keydown", function (event) {
        if (
            event.key === "Escape" &&
            logoutModal &&
            !logoutModal.classList.contains("hidden")
        ) {
            hideModal();
        }
    });
});
