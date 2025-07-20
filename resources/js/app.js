// import Alpine from "alpinejs";
import "./bootstrap";
// window.Alpine = Alpine;
// Alpine.start();
import Swal from "sweetalert2";

document.addEventListener("DOMContentLoaded", function () {
    // Basic SweetAlert handler
    window.addEventListener("swal", (event) => {
        Swal.fire(event.detail);
    });

    // Toast notification handler
    window.addEventListener("swal-toast", (event) => {
        const Toast = Swal.mixin({
            toast: true,
            position: event.detail.position || "top-end",
            showConfirmButton: false,
            timer: event.detail.timer || 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
        Toast.fire(event.detail);
    });

    // Confirmation dialog handler
    window.addEventListener("swal-confirm", (event) => {
        Swal.fire(event.detail).then((result) => {
            if (result.isConfirmed && event.detail.method) {
                // Call Livewire method if confirmed
                const component = Livewire.find(
                    event.detail.componentId || getActiveComponentId()
                );
                if (
                    component &&
                    typeof component[event.detail.method] === "function"
                ) {
                    component[event.detail.method](
                        ...(event.detail.params || [])
                    );
                } else {
                    // Fallback: dispatch event
                    window.dispatchEvent(
                        new CustomEvent(event.detail.method, {
                            detail: event.detail.params,
                        })
                    );
                }
            }
        });

        window.addEventListener("redirect-after-swal", (event) => {
            const url = event.detail.url;
            setTimeout(() => {
                window.location.href = url;
            }, 10000); // Sesuaikan penundaan sesuai kebutuhan (misalnya, 1000ms untuk 1 detik)
        });
    });

    // Loading handler
    window.addEventListener("swal-loading", (event) => {
        Swal.fire({
            ...event.detail,
            didOpen: () => {
                Swal.showLoading();
            },
        });
    });

    // Close handler
    window.addEventListener("swal-close", (event) => {
        Swal.close();
    });

    // Helper function to get active Livewire component ID
    function getActiveComponentId() {
        const activeElement = document.activeElement;
        const livewireComponent = activeElement.closest("[wire\\:id]");
        return livewireComponent
            ? livewireComponent.getAttribute("wire:id")
            : null;
    }
});

// ===== CUSTOM SWEETALERT FUNCTIONS =====

// Quick success toast
window.swalSuccess = function (message, timer = 3000) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
    });
    Toast.fire({
        icon: "success",
        title: message,
    });
};

// Quick error alert
window.swalError = function (title, text = "") {
    Swal.fire({
        icon: "error",
        title: title,
        text: text,
        confirmButtonColor: "#EF4444",
    });
};

// Quick confirmation
window.swalConfirm = function (title, text, callback) {
    Swal.fire({
        icon: "question",
        title: title,
        text: text,
        showCancelButton: true,
        confirmButtonText: "Ya",
        cancelButtonText: "Batal",
        confirmButtonColor: "#EF4444",
        cancelButtonColor: "#6B7280",
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
};

// Logout confirmation specifically
window.swalLogout = function (callback) {
    Swal.fire({
        icon: "question",
        title: "Konfirmasi Logout",
        text: "Apakah Anda yakin ingin keluar dari aplikasi?",
        showCancelButton: true,
        confirmButtonText: "Ya, Keluar",
        cancelButtonText: "Batal",
        confirmButtonColor: "#EF4444",
        cancelButtonColor: "#6B7280",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: "Logging out...",
                text: "Mohon tunggu sebentar",
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            // Execute callback
            if (callback) callback();
        }
    });
};
