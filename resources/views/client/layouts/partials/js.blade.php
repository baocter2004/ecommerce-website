
<script src="/client/js/jquery-3.3.1.min.js"></script>
<script src="/client/js/jquery-ui.js"></script>
<script src="/client/js/popper.min.js"></script>
<script src="/client/js/bootstrap.min.js"></script>
<script src="/client/js/owl.carousel.min.js"></script>
<script src="/client/js/jquery.magnific-popup.min.js"></script>
<script src="/client/js/aos.js"></script>

<script src="/client/js/main.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo Toast nhưng không tự động gọi cho Session
        window.Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
    });
</script>
