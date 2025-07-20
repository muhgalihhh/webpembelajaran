/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        // Tambahkan baris ini untuk memastikan komponen juga dipindai
        "./app/View/Components/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                "alert-success": "#38A2AC", // Warna teal dari gambar
                "alert-error": "#E57373", // Warna merah lembut untuk error
                "alert-icon-success": "#1E63B4", // Warna biru untuk ikon
                "alert-icon-error": "#D32F2F", // Warna merah untuk ikon error
            },
        },
    },
    plugins: [],
};
