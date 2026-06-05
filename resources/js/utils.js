import Swal from 'sweetalert2';

export const green = '#15a362';
export const grey = '#6c757d';

export function escapeHtml(value) {
    const element = document.createElement('div');
    element.textContent = value;
    return element.innerHTML;
}

export function fadeAndRemove(element) {
    element.style.transition = 'opacity 250ms ease';
    element.style.opacity = '0';

    setTimeout(() => element.remove(), 250);
}

export function toast(icon, title) {
    if (!title) return;

    Swal.fire({
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
    if (!text) return;

    Swal.fire({
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
    if (errors.length) {
        Swal.fire({
            icon: 'error',
            title: 'Validation',
            html: errors.map((error) => `<div>${error}</div>`).join(''),
            confirmButtonColor: green,
        });
    }
}

export function formUrl(form) {
    const url = new URL(form.action, window.location.origin);
    const params = new URLSearchParams(new FormData(form));

    for (const [key, value] of params.entries()) {
        if (!value) params.delete(key);
    }

    url.search = params.toString();
    return url;
}

export async function jsonMessage(response) {
    try {
        const data = await response.json();
        return data.message || 'The action could not be completed.';
    } catch {
        return 'The action could not be completed.';
    }
}

export function jsonHeaders(includeCsrf = false) {
    const headers = {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };

    const token = document.querySelector('meta[name="csrf-token"]')?.content;

    if (includeCsrf && token) {
        headers['X-CSRF-TOKEN'] = token;
    }

    return headers;
}
