import Swal from 'sweetalert2';

import { alert, fadeAndRemove, green, grey, jsonHeaders, jsonMessage, toast } from './utils';

// Reload the overview list after a decision changes the counts.
async function refreshOverview() {
    const results = document.querySelector('[data-overview-results]');
    if (!results) return;

    results.style.opacity = '0.55';

    try {
        const response = await fetch(window.location.href, {
            headers: jsonHeaders(),
        });

        if (!response.ok) throw new Error('The overview could not be refreshed.');

        const data = await response.json();
        results.innerHTML = data.html;
    } catch (error) {
        alert('error', 'Filtering failed', error.message);
    } finally {
        results.style.opacity = '1';
    }
}

// Open the modal and load its HTML on demand.
function listenForModalOpening() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-registration-modal-url]');
        if (!button) return;

        const modal = document.querySelector('#registrations-modal');
        const content = modal?.querySelector('[data-registrations-modal-content]');
        if (!modal || !content) return;

        content.innerHTML = '<div class="modal-body text-center text-muted py-5">Loading...</div>';
        window.bootstrap.Modal.getOrCreateInstance(modal).show();

        try {
            const response = await fetch(button.dataset.registrationModalUrl, {
                headers: jsonHeaders(),
            });

            if (!response.ok) throw new Error('The registrations could not be loaded.');

            const data = await response.json();
            content.innerHTML = data.html;
        } catch (error) {
            window.bootstrap.Modal.getInstance(modal)?.hide();
            alert('error', 'Loading failed', error.message);
        }
    });
}

// Confirm and process accept/reject actions from the modal.
function listenForDecision() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-live-registration-form]');
        if (!form) return;

        event.preventDefault();

        const action = form.dataset.actionType;
        const name = form.closest('[data-registration-row]')?.querySelector('td.cell')?.textContent?.trim() || 'this user';
        const isAccept = action === 'accept';
        const confirmed = await Swal.fire({
            icon: isAccept ? 'question' : 'warning',
            title: isAccept ? 'Accept registration?' : 'Reject registration?',
            text: `${isAccept ? 'Accept' : 'Reject'} ${name} for this activity?`,
            confirmButtonText: isAccept ? 'Yes, accept' : 'Yes, reject',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: green,
            cancelButtonColor: grey,
        });

        if (!confirmed.isConfirmed) return;

        Swal.fire({
            title: 'Updating...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });

        const button = form.querySelector('button[type="submit"]');
        const row = form.closest('[data-registration-row]');
        const modal = form.closest('.modal');
        const isLastRow = row?.closest('tbody')?.querySelectorAll('[data-registration-row]').length === 1;
        if (button) button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: jsonHeaders(true),
                body: new FormData(form),
            });

            if (!response.ok) throw new Error(await jsonMessage(response));

            const data = await response.json();
            await refreshOverview();
            Swal.close();

            if (isLastRow && modal) {
                window.bootstrap.Modal.getInstance(modal)?.hide();
            } else if (row) {
                fadeAndRemove(row);
            }

            toast('success', data.message || 'Registration updated.');
        } catch (error) {
            Swal.close();
            if (button) button.disabled = false;
            alert('error', 'Live action failed', error.message);
        }
    });
}

// Enable the admin overview modal and its live decisions.
export function initAdminRegistrationActions() {
    listenForModalOpening();
    listenForDecision();
}
