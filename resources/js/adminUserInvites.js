import { escapeHtml, fadeAndRemove, formUrl, jsonHeaders, jsonMessage } from './utils';
import { alert, green, grey, toast } from './feedback';
import { applyPreferenceSelections, clearFilterForm, initSelect2, refreshPreferenceHighlights, uncheckMatchPreferences } from './selects';

export function initUserInviteActions() {
    let filterTimer;

    async function loadIntoContent(url, content) {
        content.innerHTML = '<div class="modal-body text-center text-muted py-5">Loading...</div>';

        const response = await fetch(url, {
            headers: jsonHeaders(),
            credentials: 'same-origin',
        });

        if (!response.ok) throw new Error('The invite options could not be loaded.');

        const data = await response.json();
        content.innerHTML = data.html;
        initSelect2(content);

        const form = content.querySelector('[data-user-invite-filter-form]');
        if (form) refreshPreferenceHighlights(form);
    }

    async function loadFilterResults(form) {
        const results = form.closest('.modal')?.querySelector('[data-user-invite-results]');
        if (!results) return;

        results.style.opacity = '0.55';

        try {
            const response = await fetch(formUrl(form), {
                headers: jsonHeaders(),
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

        if (resetButton) {
            const form = resetButton.closest('[data-user-invite-filter-form]');
            if (!form) return;

            clearFilterForm(form);
            clearTimeout(filterTimer);
            loadFilterResults(form);
            return;
        }

        if (button.disabled) return;

        const modal = document.querySelector('#user-invite-modal');
        const content = modal?.querySelector('[data-user-invite-modal-content]');
        if (!modal || !content) return;

        window.bootstrap?.Modal.getOrCreateInstance(modal).show();

        try {
            await loadIntoContent(button.dataset.userInviteModalUrl, content);
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

        if (!event.target.matches('[data-match-preferences]')) {
            uncheckMatchPreferences(form);
        }

        clearTimeout(filterTimer);
        filterTimer = setTimeout(() => loadFilterResults(form), 350);
    });

    document.addEventListener('change', (event) => {
        const form = event.target.closest('[data-user-invite-filter-form]');
        if (!form) return;

        if (event.target.matches('[data-match-preferences]')) {
            applyPreferenceSelections(form);
        } else {
            uncheckMatchPreferences(form);
            refreshPreferenceHighlights(form);
        }

        clearTimeout(filterTimer);
        loadFilterResults(form);
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-admin-invite-form]');
        if (!form || form.dataset.confirmed === 'true') return;

        event.preventDefault();

        const userName = form.dataset.userName || 'this user';
        const activityTitle = form.dataset.activityTitle || 'this activity';
        const activityDate = form.dataset.activityDate || '';
        const activityLocation = form.dataset.activityLocation || '';

        const confirmed = await window.Swal.fire({
            icon: 'question',
            title: 'Send invitation?',
            html: `
                <div class="text-start">
                    <div class="mb-2">Invite <strong>${escapeHtml(userName)}</strong> to:</div>
                    <div class="fw-semibold mb-2">${escapeHtml(activityTitle)}</div>
                    ${activityDate ? `<div><strong>Date:</strong> ${escapeHtml(activityDate)}</div>` : ''}
                    ${activityLocation ? `<div><strong>Location:</strong> ${escapeHtml(activityLocation)}</div>` : ''}
                </div>
            `,
            confirmButtonText: 'Yes, invite',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: green,
            cancelButtonColor: grey,
        });

        if (!confirmed.isConfirmed) return;

        const button = document.querySelector(`[form="${CSS.escape(form.id)}"]`);
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
            if (button) button.disabled = false;
            alert('error', 'Invitation failed', error.message);
        }
    });
}
