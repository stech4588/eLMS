import Swal from 'sweetalert2';

/**
 * Premium Toast Notification Utility
 * @param {string} message - The message to display
 * @param {string} type - 'success', 'error', 'warning', 'info'
 */
export const showToast = (message, type = 'success') => {
    const isDark = document.documentElement.classList.contains('dark');
    
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: isDark ? '#1A2C38' : '#fff',
        color: isDark ? '#fff' : '#1C355E',
        customClass: {
            popup: 'elms-v3-toast-popup',
        },
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
};
