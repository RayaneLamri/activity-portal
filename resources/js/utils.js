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
