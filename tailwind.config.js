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
        extend: {},
    },
    plugins: [],
};
