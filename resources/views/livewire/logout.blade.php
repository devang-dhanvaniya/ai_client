<a class="d-flex align-items-center text-muted small py-2 border-top text-decoration-none"  href="/logout" wire:click.prevent="logout">
    <i class="fa-solid fa-arrow-right-from-bracket me-2 text-secondary"></i>
    <span>logout</span>
</a>
<script>
    document.getElementById('logoutButton').addEventListener('click', function() {
        localStorage.removeItem('selectedDateRangeStart');
        localStorage.removeItem('selectedDateRangeEnd');
    });
</script>
