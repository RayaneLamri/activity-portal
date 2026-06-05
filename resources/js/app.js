import './bootstrap';

import { applyPreferenceSelections, initFilterResults } from './filterResults';
import { initAdminRegistrationActions } from './adminRegistrations';
import { initUserInviteActions } from './adminUserInvites';
import { initAgeRangeSliders, initSelect2, refreshSelect2 } from './filtersUi';
import { showFlashMessages } from './utils';
import { initUserRegistrationConfirmations } from './userRegistrations';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function initGlobalUi() {
    showFlashMessages();
    initSelect2();
    initAgeRangeSliders();

    document.querySelectorAll('[data-match-preferences]:checked').forEach((input) => {
        const form = input.closest('form');
        if (!form) return;

        applyPreferenceSelections(form);
        refreshSelect2(form);
    });
}

function initUserRegistrationConfirmationsWhenPresent() {
    if (!document.querySelector('[data-registration-request-form], [data-invitation-response-form]')) return;

    initUserRegistrationConfirmations();
}

function initUserActivitiesPage() {
    initFilterResults(
        '[data-live-activity-filter-form]',
        '[data-activity-results]',
        '[data-live-activity-filter-reset]',
        'The activity list could not be refreshed.',
    );
}

function initAdminOverviewPage() {
    if (!document.querySelector('[data-live-filter-form], #registrations-modal')) return;

    initFilterResults(
        '[data-live-filter-form]',
        '[data-overview-results]',
        '[data-live-filter-reset]',
        'The overview could not be refreshed.',
    );
    initAdminRegistrationActions();
}

function initAdminInvitePage() {
    if (!document.querySelector('[data-user-invite-modal-url], #user-invite-modal')) return;

    initUserInviteActions();
}

function initApp() {
    initGlobalUi();
    initUserActivitiesPage();
    initAdminOverviewPage();
    initAdminInvitePage();
    initUserRegistrationConfirmationsWhenPresent();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp, { once: true });
} else {
    initApp();
}
