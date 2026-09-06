<!-- SweetAlert2 Toast Integration for NutriGo -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .swal2-container.swal2-top-end {
        top: 1rem !important;
        right: 1rem !important;
    }
    .swal2-popup.nutrigo-toast-popup {
        border-radius: 1.25rem !important;
        padding: 0.85rem 1.25rem !important;
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.3), 0 10px 15px -5px rgba(0, 0, 0, 0.15) !important;
        font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
    }
    .swal2-popup.nutrigo-toast-popup .swal2-title {
        font-family: 'Outfit', sans-serif !important;
        font-weight: 800 !important;
        font-size: 0.95rem !important;
        margin: 0 !important;
    }
    .swal2-popup.nutrigo-toast-popup .swal2-html-container {
        font-size: 0.75rem !important;
        font-weight: 500 !important;
        margin: 0.2rem 0 0 0 !important;
        line-height: 1.35 !important;
    }
    .swal2-popup.nutrigo-toast-popup .swal2-timer-progress-bar {
        border-radius: 9999px !important;
        height: 3px !important;
    }
</style>

<script>
    window.nutriToast = function(type, message, title = '') {
        if (!message) return;
        
        let background = '#0F4A2B'; // Default Nutri-900
        let iconColor = '#A3E635';   // Limey-400
        let progressColor = '#A3E635';
        let defaultTitle = 'Success';

        if (type === 'error') {
            background = '#881337'; // Rose-900
            iconColor = '#FDA4AF';  // Rose-300
            progressColor = '#F43F5E';
            defaultTitle = 'Notice';
        } else if (type === 'warning') {
            background = '#78350F'; // Amber-900
            iconColor = '#FDE68A';  // Amber-200
            progressColor = '#F59E0B';
            defaultTitle = 'Attention';
        } else if (type === 'info') {
            background = '#0C4A6E'; // Sky-900
            iconColor = '#7DD3FC';  // Sky-300
            progressColor = '#0284C7';
            defaultTitle = 'Information';
        }

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: title || defaultTitle,
            html: message,
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: background,
            color: '#FFFFFF',
            iconColor: iconColor,
            customClass: {
                popup: 'nutrigo-toast-popup',
            },
            didOpen: (toast) => {
                const progressBar = toast.querySelector('.swal2-timer-progress-bar');
                if (progressBar) {
                    progressBar.style.backgroundColor = progressColor;
                }
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            window.nutriToast('success', @json(session('success')), 'Success');
        @endif

        @if(session('error'))
            window.nutriToast('error', @json(session('error')), 'Notice');
        @endif

        @if(session('status'))
            window.nutriToast('info', @json(session('status')), 'Status Update');
        @endif

        @if(session('info'))
            window.nutriToast('info', @json(session('info')), 'Information');
        @endif

        @if(session('warning'))
            window.nutriToast('warning', @json(session('warning')), 'Warning');
        @endif

        @if($errors->any())
            window.nutriToast('error', @json($errors->first()), 'Validation Error');
        @endif
    });
</script>
