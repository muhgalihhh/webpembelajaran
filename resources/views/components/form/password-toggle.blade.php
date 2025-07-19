<script>
    function togglePasswordVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        if (input) {
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            iconElement.innerHTML = type === 'password' ? '<i class="fa fa-eye-slash"></i>' :
                '<i class="fa fa-eye"></i>';
        }
    }
</script>
