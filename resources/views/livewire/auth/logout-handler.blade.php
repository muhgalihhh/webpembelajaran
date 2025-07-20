<div>
    {{-- This component handles logout via SweetAlert --}}
    {{-- No visual output needed, just JavaScript event handlers --}}
</div>

<script>
    // Listen for logout button clicks
    document.addEventListener('DOMContentLoaded', function() {
        // Handle logout confirmation from any logout button
        window.addEventListener('show-logout-confirmation', event => {
            @this.showLogoutConfirmation();
        });

        // Alternative: Direct JavaScript logout confirmation
        window.addEventListener('logout-confirm', event => {
            swalLogout(() => {
                @this.confirmLogout();
            });
        });
    });
</script>
