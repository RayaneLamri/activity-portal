import { fadeAndRemove, jsonHeaders, jsonMessage } from './utils';
import { alert, green, grey, toast } from './feedback';

export function initAdminRegistrationActions(overview) {
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
                headers: jsonHeaders(),
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
            cancelButtonColor: grey,
        });

        if (!confirmed.isConfirmed) return;

        const button = form.querySelector('button[type="submit"]');
        const row = form.closest('[data-registration-row]');
        const modal = form.closest('.modal');
        const isLastRow = row?.closest('tbody')?.querySelectorAll('[data-registration-row]').length === 1;
        if (button) button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: jsonHeaders(true),
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
