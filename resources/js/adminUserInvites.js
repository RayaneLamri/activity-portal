import Swal from 'sweetalert2';

import { alert, escapeHtml, fadeAndRemove, green, grey, jsonHeaders, jsonMessage, toast } from './utils';
import { initAgeRangeSliders, initSelect2 } from './filtersUi';
import { initFilterResults } from './filterResults';

// Open the modal and load its filter form on demand.
function listenForModalOpening() {
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-user-invite-modal-url]');
        if (!button || button.disabled) return;

        const modal = document.querySelector('#user-invite-modal');
        const content = modal?.querySelector('[data-user-invite-modal-content]');
        if (!modal || !content) return;

        const modalInstance = window.bootstrap.Modal.getOrCreateInstance(modal);

        try {
            content.innerHTML = '<div class="modal-body text-center text-muted py-5">Loading...</div>';

            const response = await fetch(button.dataset.userInviteModalUrl, {
                headers: jsonHeaders(),
            });

            if (!response.ok) throw new Error('The invite options could not be loaded.');

            const data = await response.json();
            content.innerHTML = data.html;
            initSelect2(content);
            initAgeRangeSliders(content);
            initFilterResults(
                '[data-user-invite-filter-form]',
                '[data-user-invite-results]',
                '[data-user-invite-filter-reset]',
                'The invite options could not be filtered.',
            );

            modalInstance.show();
        } catch (error) {
            alert('error', 'Loading failed', error.message);
        }
    });
}

// Confirm and submit one invitation from the modal results.
function listenForSubmission() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-admin-invite-form]');
        if (!form || form.dataset.confirmed === 'true') return;

        event.preventDefault();

        const userName = form.dataset.userName || 'this user';
        const activityTitle = form.dataset.activityTitle || 'this activity';
        const activityDate = form.dataset.activityDate || '';
        const activityLocation = form.dataset.activityLocation || '';
        const html = `
            <div class="text-start">
                <div class="mb-2">Invite <strong>${escapeHtml(userName)}</strong> to:</div>
                <div class="fw-semibold mb-2">${escapeHtml(activityTitle)}</div>
                ${activityDate ? `<div><strong>Date:</strong> ${escapeHtml(activityDate)}</div>` : ''}
                ${activityLocation ? `<div><strong>Location:</strong> ${escapeHtml(activityLocation)}</div>` : ''}
            </div>
        `;
        const confirmed = await Swal.fire({
            icon: 'question',
            title: 'Send invitation?',
            html,
            confirmButtonText: 'Yes, invite',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: green,
            cancelButtonColor: grey,
        });

        if (!confirmed.isConfirmed) return;

        Swal.fire({
            title: 'Sending invitation...',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => Swal.showLoading(),
        });

        const button = document.querySelector(`[form="${CSS.escape(form.id)}"]`);
        if (button) button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: jsonHeaders(true),
                body: new FormData(form),
            });

            if (!response.ok) throw new Error(await jsonMessage(response));

            const data = await response.json();
            Swal.close();
            const row = form.closest('[data-admin-invite-row]');

            if (row) {
                fadeAndRemove(row);
            } else if (button) {
                button.textContent = 'Invited';
                button.classList.remove('app-btn-primary');
                button.classList.add('app-btn-secondary');
            }

            toast('success', data.message || 'Invitation sent.');
        } catch (error) {
            Swal.close();
            if (button) button.disabled = false;
            alert('error', 'Invitation failed', error.message);
        }
    });
}

// Enable the admin invite modal and the final invite action.
export function initUserInviteActions() {
    listenForModalOpening();
    listenForSubmission();
}
