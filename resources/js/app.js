import './bootstrap';
import './alpine';

import { liveFilters } from './liveFilters';
import { initAdminRegistrationActions } from './adminRegistrations';
import { initUserInviteActions } from './adminUserInvites';
import { initSelect2, applyPreferenceSelections, matchPreferenceInput, refreshPreferenceHighlights } from './selects';
import { showFlashMessages } from './feedback';
import { initUserRegistrationConfirmations } from './userRegistrations';

document.addEventListener('DOMContentLoaded', () => {
    showFlashMessages();
    initSelect2();

    document.querySelectorAll('form').forEach((form) => {
        if (matchPreferenceInput(form)?.checked) {
            applyPreferenceSelections(form);
        } else {
            refreshPreferenceHighlights(form);
        }
    });

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

    initAdminRegistrationActions(overview);
    initUserInviteActions();
    initUserRegistrationConfirmations();
});
