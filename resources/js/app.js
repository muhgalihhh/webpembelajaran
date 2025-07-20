// import Alpine from "alpinejs";
import "./bootstrap";
// window.Alpine = Alpine;
// Alpine.start();
import Swal from "sweetalert2";

// Configure SweetAlert2 defaults (optional, but good for consistency)
// You can also move this to the config/sweetalert.php file and pass it via events
Swal.mixin({
    // Example global defaults for all Swal.fire calls
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
});

// Event listener for basic SweetAlerts (fire)
Livewire.on(import.meta.env.VITE_SWAL_FIRE_EVENT || "swal:fire", (data) => {
    const options = data[0]; // Livewire dispatches an array, so take the first element
    Swal.fire(options);
    if (options.isLoading) {
        Swal.showLoading();
    }
});

// Event listener for SweetAlert toasts
Livewire.on(import.meta.env.VITE_SWAL_TOAST_EVENT || "swal:toast", (data) => {
    const options = data[0];
    Swal.fire(options);
});

// Event listener for SweetAlert confirmation dialogs
Livewire.on(
    import.meta.env.VITE_SWAL_CONFIRM_EVENT || "swal:confirm",
    (data) => {
        const options = data[0];
        const confirmMethod = options.confirmMethod;
        const confirmParams = options.confirmParams || [];

        Swal.fire(options).then((result) => {
            if (result.isConfirmed) {
                // Dispatch to Livewire component
                Livewire.dispatch(
                    import.meta.env.VITE_SWAL_CONFIRMED_EVENT ||
                        "swal:confirmed",
                    {
                        method: confirmMethod,
                        params: confirmParams,
                    }
                );
            }
        });
    }
);

// Event listener to close any open SweetAlert
Livewire.on(import.meta.env.VITE_SWAL_CLOSE_EVENT || "swal:close", () => {
    Swal.close();
});
