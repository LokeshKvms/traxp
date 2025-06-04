<!-- SweetAlert2 CSS and JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: @json(session('success'))
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: @json(session('error'))
        });
    @endif

    @if(session('warning'))
        Toast.fire({
            icon: 'warning',
            title: @json(session('warning'))
        });
    @endif

    @if(session('info'))
        Toast.fire({
            icon: 'info',
            title: @json(session('info'))
        });
    @endif
});
</script>
