import { alert, formUrl, jsonHeaders } from './utils';
import { initSelect2, refreshSelect2 } from './filtersUi';

function setAgeRange(form, slider, min, max) {
    const minInput = form.querySelector('[data-age-range-min]');
    const maxInput = form.querySelector('[data-age-range-max]');
    const label = form.querySelector('[data-age-range-label]');
    const $slider = window.jQuery?.(slider);

    if (minInput) minInput.value = String(min);
    if (maxInput) maxInput.value = String(max);
    if (label) label.textContent = `${min} - ${max}`;
    if ($slider?.hasClass('ui-slider')) {
        $slider.slider('values', [min, max]);
    }
}

export function applyPreferenceSelections(form) {
    if (!form.querySelector('[data-match-preferences]')?.checked) return;

    form.querySelectorAll('input[name="search"]').forEach((input) => {
        input.value = '';
    });

    form.querySelectorAll('select[data-select-enhanced][data-preference-values]').forEach((select) => {
        const values = JSON.parse(select.dataset.preferenceValues || '[]').map(String);
        if (values.length === 0) return;

        for (const option of select.options) {
            option.selected = values.includes(String(option.value));
        }

        window.jQuery?.(select).trigger('change.select2');
    });

    form.querySelectorAll('[data-age-range-slider]').forEach((slider) => {
        setAgeRange(
            form,
            slider,
            Number(slider.dataset.preferenceMin || slider.dataset.min || 3),
            Number(slider.dataset.preferenceMax || slider.dataset.max || 18),
        );
    });
}

function clearFilterForm(form) {
    form.querySelectorAll('input, select, textarea').forEach((field) => {
        if (field.type === 'hidden') return;

        if (field.matches('select')) {
            for (const option of field.options) {
                option.selected = false;
            }
        } else if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = false;
        } else {
            field.value = '';
        }
    });

    form.querySelectorAll('[data-age-range-slider]').forEach((slider) => {
        setAgeRange(
            form,
            slider,
            Number(slider.dataset.min || 3),
            Number(slider.dataset.max || 18),
        );
    });
}

async function loadResults(form, results, errorText, url = formUrl(form), updateAddress = true) {
    results.style.opacity = '0.55';

    try {
        const response = await fetch(url, {
            headers: jsonHeaders(),
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

export function initFilterResults(formSelector, resultsSelector, resetSelector, errorText) {
    const form = document.querySelector(formSelector);
    const results = document.querySelector(resultsSelector);
    const resetButton = document.querySelector(resetSelector);

    if (!form || !results || !resetButton) return;

    let timer;
    const matchPreferences = form.querySelector('[data-match-preferences]');

    function clearPreferenceMode() {
        if (!matchPreferences?.checked) return;

        matchPreferences.checked = false;
        refreshSelect2(form);
    }

    function load(url = formUrl(form), updateAddress = true) {
        loadResults(form, results, errorText, url, updateAddress);
    }

    function loadSoon() {
        clearTimeout(timer);
        timer = setTimeout(load, 350);
    }

    function loadNow() {
        clearTimeout(timer);
        load();
    }

    form.addEventListener('input', (event) => {
        if (!event.target.matches('input')) return;

        if (!event.target.matches('[data-match-preferences]')) {
            clearPreferenceMode();
        }

        loadSoon();
    });

    form.addEventListener('change', (event) => {
        if (event.target.matches('[data-match-preferences]')) {
            applyPreferenceSelections(form);
            refreshSelect2(form);
        } else {
            clearPreferenceMode();
        }

        loadNow();
    });

    resetButton.addEventListener('click', () => {
        clearFilterForm(form);
        refreshSelect2(form);
        load();
    });

    results.addEventListener('click', (event) => {
        const link = event.target.closest('.pagination a');
        if (!link) return;

        event.preventDefault();
        load(new URL(link.href));
    });
}
