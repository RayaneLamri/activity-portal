import { formUrl, jsonHeaders } from './utils';
import { alert } from './feedback';
import { initSelect2, applyPreferenceSelections, clearFilterForm, refreshPreferenceHighlights, uncheckMatchPreferences } from './selects';

export function liveFilters(formSelector, resultsSelector, resetSelector, errorText) {
    const form = document.querySelector(formSelector);
    const results = document.querySelector(resultsSelector);
    if (!form || !results) return null;

    let timer;

    async function load(url = formUrl(form), updateAddress = true) {
        results.style.opacity = '0.55';

        try {
            const response = await fetch(url, {
                headers: jsonHeaders(),
                credentials: 'same-origin',
            });

            if (!response.ok) throw new Error(errorText);

            const data = await response.json();
            results.innerHTML = data.html;
            initSelect2(results);

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
        if (!event.target.matches('input')) return;

        if (!event.target.matches('[data-match-preferences]')) {
            uncheckMatchPreferences(form);
        }

        delayedLoad();
    });

    form.addEventListener('change', (event) => {
        if (event.target.matches('[data-match-preferences]')) {
            applyPreferenceSelections(form);
        } else {
            uncheckMatchPreferences(form);
            refreshPreferenceHighlights(form);
        }

        delayedLoad(0);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        load();
    });

    document.querySelector(resetSelector)?.addEventListener('click', () => {
        clearFilterForm(form);
        load(formUrl(form));
    });

    results.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');
        if (!link) return;

        event.preventDefault();
        load(new URL(link.href));
    });

    return { load };
}
