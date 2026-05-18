import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const green = '#15a362';

function toast(icon, title) {
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

function alert(icon, title, text) {
    if (!window.Swal || !text) return;

    window.Swal.fire({
        icon,
        title,
        text,
        confirmButtonColor: green,
    });
}

function formUrl(form) {
    const url = new URL(form.action, window.location.origin);
    const params = new URLSearchParams(new FormData(form));

    for (const [key, value] of params.entries()) {
        if (!value) params.delete(key);
    }

    url.search = params.toString();
    return url;
}

async function jsonMessage(response) {
    try {
        const data = await response.json();
        return data.message || 'The action could not be completed.';
    } catch {
        return 'The action could not be completed.';
    }
}

function showFlashMessages() {
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

function liveFilters(formSelector, resultsSelector, resetSelector, errorText) {
    const form = document.querySelector(formSelector);
    const results = document.querySelector(resultsSelector);
    if (!form || !results) return null;

    let timer;

    async function load(url = formUrl(form), updateAddress = true) {
        results.style.opacity = '0.55';

        try {
            const response = await fetch(url, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error(errorText);

            const data = await response.json();
            results.innerHTML = data.html;

            if (updateAddress) {
                window.history.replaceState({}, '', url);
            }
        } catch (error) {
            alert('error', 'Filtering failed', error.message || errorText);
        } finally {
            results.style.opacity = '1';
        }
    }

    function delayedLoad(delay = 350) {
        clearTimeout(timer);
        timer = setTimeout(() => load(), delay);
    }

    form.addEventListener('input', (event) => {
        if (event.target.matches('input')) delayedLoad();
    });

    form.addEventListener('change', () => delayedLoad(0));

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        load();
    });

    document.querySelector(resetSelector)?.addEventListener('click', () => {
        form.reset();
        load(new URL(form.action, window.location.origin));
    });

    results.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');
        if (!link) return;

        event.preventDefault();
        load(new URL(link.href));
    });

    return { load };
}

function fadeAndRemove(row) {
    row.style.transition = 'opacity 250ms ease';
    row.style.opacity = '0';

    setTimeout(() => row.remove(), 250);
}

function adminActions(overview) {
    if (!overview) return;

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-registration-modal-url]');
        if (!button) return;

        const modal = document.querySelector('#registrations-modal');
        const content = modal?.querySelector('[data-registrations-modal-content]');
        if (!modal || !content) return;

        content.innerHTML = '<div class="modal-body text-center text-muted py-5">Loading...</div>';
        window.bootstrap?.Modal.getOrCreateInstance(modal).show();

        try {
            const response = await fetch(button.dataset.registrationModalUrl, {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error('The registrations could not be loaded.');

            const data = await response.json();
            content.innerHTML = data.html;
        } catch (error) {
            window.bootstrap?.Modal.getInstance(modal)?.hide();
            alert('error', 'Loading failed', error.message);
        }
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-live-registration-form]');
        if (!form) return;

        event.preventDefault();

        const action = form.dataset.actionType;
        const name = form.closest('[data-registration-row]')?.querySelector('td.cell')?.textContent?.trim() || 'this user';
        const confirmed = await window.Swal.fire({
            icon: action === 'accept' ? 'question' : 'warning',
            title: action === 'accept' ? 'Accept registration?' : 'Reject registration?',
            text: `${action === 'accept' ? 'Accept' : 'Reject'} ${name} for this activity?`,
            confirmButtonText: action === 'accept' ? 'Yes, accept' : 'Yes, reject',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: green,
            cancelButtonColor: '#6c757d',
        });

        if (!confirmed.isConfirmed) return;

        const button = form.querySelector('button[type="submit"]');
        const row = form.closest('[data-registration-row]');
        const modal = form.closest('.modal');
        const tableRows = row?.closest('tbody')?.querySelectorAll('[data-registration-row]') || [];
        const isLastRow = tableRows.length === 1;
        if (button) button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                credentials: 'same-origin',
                body: new FormData(form),
            });

            if (!response.ok) throw new Error(await jsonMessage(response));

            const data = await response.json();
            await overview.load(new URL(window.location.href), false);

            if (isLastRow && modal) {
                window.bootstrap?.Modal.getInstance(modal)?.hide();
            } else if (row) {
                fadeAndRemove(row);
            }

            toast('success', data.message || 'Registration updated.');
        } catch (error) {
            if (button) button.disabled = false;
            alert('error', 'Live action failed', error.message);
        }
    });
}

function userInviteActions() {
    let filterTimer;

    async function loadIntoContent(url, content, loadingText = 'Loading...') {
        content.innerHTML = `<div class="modal-body text-center text-muted py-5">${loadingText}</div>`;

        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) throw new Error('The invite options could not be loaded.');

        const data = await response.json();
        content.innerHTML = data.html;
    }

    async function loadFilterResults(form) {
        const modal = form.closest('.modal');
        const results = modal?.querySelector('[data-user-invite-results]');
        if (!results) return;

        results.style.opacity = '0.55';

        try {
            const response = await fetch(formUrl(form), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error('The invite options could not be filtered.');

            const data = await response.json();
            results.innerHTML = data.html;
        } catch (error) {
            alert('error', 'Filtering failed', error.message);
        } finally {
            results.style.opacity = '1';
        }
    }

    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-user-invite-modal-url]');
        const resetButton = event.target.closest('[data-user-invite-filter-reset]');

        if (!button && !resetButton) return;
        if (button?.disabled) return;

        const modal = document.querySelector('#user-invite-modal');
        const content = modal?.querySelector('[data-user-invite-modal-content]');
        if (!modal || !content) return;

        window.bootstrap?.Modal.getOrCreateInstance(modal).show();

        try {
            await loadIntoContent(button?.dataset.userInviteModalUrl || resetButton.dataset.resetUrl, content);
        } catch (error) {
            window.bootstrap?.Modal.getInstance(modal)?.hide();
            alert('error', 'Loading failed', error.message);
        }
    });

    document.addEventListener('submit', (event) => {
        const form = event.target.closest('[data-user-invite-filter-form]');
        if (!form) return;

        event.preventDefault();
        loadFilterResults(form);
    });

    document.addEventListener('input', (event) => {
        const form = event.target.closest('[data-user-invite-filter-form]');
        if (!form || !event.target.matches('input')) return;

        clearTimeout(filterTimer);
        filterTimer = setTimeout(() => loadFilterResults(form), 350);
    });

    document.addEventListener('change', (event) => {
        const form = event.target.closest('[data-user-invite-filter-form]');
        if (!form) return;

        clearTimeout(filterTimer);
        loadFilterResults(form);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    showFlashMessages();

    liveFilters(
        '[data-live-activity-filter-form]',
        '[data-activity-results]',
        '[data-live-activity-filter-reset]',
        'The activity list could not be refreshed.',
        );

    const overview = liveFilters(
        '[data-live-filter-form]',
        '[data-overview-results]',
        '[data-live-filter-reset]',
        'The overview could not be refreshed.',
        );

    adminActions(overview);
    userInviteActions();
});
