import { escapeHtml } from './utils';
import { green, grey } from './feedback';

function activityHtml(form) {
    const title = form.dataset.activityTitle || 'this activity';
    const date = form.dataset.activityDate || '';
    const location = form.dataset.activityLocation || '';

    return `
        <div class="text-start">
            <div class="fw-semibold mb-2">${escapeHtml(title)}</div>
            ${date ? `<div><strong>Date:</strong> ${escapeHtml(date)}</div>` : ''}
            ${location ? `<div><strong>Location:</strong> ${escapeHtml(location)}</div>` : ''}
        </div>
    `;
}

async function confirmWithSwal(options) {
    const confirmed = await window.Swal.fire({
        icon: options.icon,
        title: options.title,
        html: activityHtml(options.form),
        confirmButtonText: options.confirmButtonText,
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: green,
        cancelButtonColor: grey,
    });

    return confirmed.isConfirmed;
}

export function initUserRegistrationConfirmations() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-registration-request-form]');
        if (!form || form.dataset.confirmed === 'true') return;

        event.preventDefault();

        const confirmed = await confirmWithSwal({
            form,
            icon: 'question',
            title: 'Request registration?',
            confirmButtonText: 'Yes, request',
        });

        if (!confirmed) return;

        const button = form.querySelector('button[type="submit"]');
        if (button) button.disabled = true;

        form.dataset.confirmed = 'true';
        form.submit();
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-invitation-response-form]');
        if (!form || form.dataset.confirmed === 'true') return;

        event.preventDefault();

        const isAccept = form.dataset.actionType === 'accept';
        const confirmed = await confirmWithSwal({
            form,
            icon: isAccept ? 'question' : 'warning',
            title: isAccept ? 'Accept invitation?' : 'Decline invitation?',
            confirmButtonText: isAccept ? 'Yes, accept' : 'Yes, decline',
        });

        if (!confirmed) return;

        const button = form.querySelector('button[type="submit"]');
        if (button) button.disabled = true;

        form.dataset.confirmed = 'true';
        form.submit();
    });
}
