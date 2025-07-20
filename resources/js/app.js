// import Alpine from "alpinejs";
import "./bootstrap";
// window.Alpine = Alpine;
// Alpine.start();
import Swal from "sweetalert2";

document.addEventListener("livewire:init", () => {
    Livewire.on("swal:fire", (data) => {
        Swal.fire(data[0]);
    });

    Livewire.on("swal:confirm", (data) => {
        Swal.fire(data[0]).then((result) => {
            if (result.isConfirmed && data[0].method) {
                Livewire.dispatch("swal:confirmed", {
                    method: data[0].method,
                    params: data[0].params,
                });
            }
        });
    });

    Livewire.on("swal:toast", (data) => {
        const Toast = Swal.mixin({
            toast: true,
            position: data[0].position || "top-end",
            showConfirmButton: false,
            timer: data[0].timer || 3000,
            timerProgressBar: true,
        });

        Toast.fire({
            icon: data[0].type,
            title: data[0].message,
        });
    });
});
