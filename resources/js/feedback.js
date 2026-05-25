export const green = '#15a362';
export const grey = '#6c757d';

export function toast(icon, title) {
    if (!window.Swal || !title) return;

    window.Swal.fire({
        toast: true,
        position: 'top-end',
        icon,
        title,
        showConfirmButton: false,
        timer: 2600,
        timerProgressBar: true,
    });
}

export function alert(icon, title, text) {
    if (!window.Swal || !text) return;

    window.Swal.fire({
        icon,
        title,
        text,
        confirmButtonColor: green,
    });
}

export function showFlashMessages() {
    const body = document.body;
    if (!body) return;

    toast('success', body.dataset.flashSuccess);
    alert('warning', 'Attention', body.dataset.flashWarning);
    alert('error', 'Erreur', body.dataset.flashError);

    const errors = JSON.parse(body.dataset.validationErrors || '[]');
    if (errors.length && window.Swal) {
        window.Swal.fire({
            icon: 'error',
            title: 'Validation',
            html: errors.map((error) => `<div>${error}</div>`).join(''),
            confirmButtonColor: green,
        });
    }
}
