function preferenceValues(select) {
    return JSON.parse(select.dataset.preferenceValues || '[]').map(String);
}

export function matchPreferenceInput(form) {
    return form.querySelector('[data-match-preferences]');
}

export function refreshPreferenceHighlights(form) {
    form.querySelectorAll('select[data-select-enhanced]').forEach((select) => {
        window.jQuery?.(select).trigger('change.select2');
    });
}

export function uncheckMatchPreferences(form) {
    const input = matchPreferenceInput(form);
    if (!input?.checked) return;

    input.checked = false;
    refreshPreferenceHighlights(form);
}

export function applyPreferenceSelections(form) {
    if (!matchPreferenceInput(form)?.checked) {
        refreshPreferenceHighlights(form);
        return;
    }

    form.querySelectorAll('select[data-select-enhanced][data-preference-values]').forEach((select) => {
        const values = preferenceValues(select);
        if (values.length === 0) return;

        for (const option of select.options) {
            option.selected = values.includes(String(option.value));
        }

        window.jQuery?.(select).trigger('change.select2');
    });

    refreshPreferenceHighlights(form);
}

export function initSelect2(root = document) {
    if (!window.jQuery?.fn?.select2) return;

    root.querySelectorAll('select[data-select-enhanced]').forEach((select) => {
        const $select = window.jQuery(select);
        if ($select.data('select2')) return;

        $select.select2({
            width: '100%',
            closeOnSelect: false,
            dropdownParent: $select.closest('.modal').length ? $select.closest('.modal') : window.jQuery(document.body),
            placeholder: select.dataset.placeholder || '',
        });

        $select.on('select2:select select2:unselect select2:clear', () => {
            select.dispatchEvent(new Event('change', { bubbles: true }));
        });
    });

    root.querySelectorAll('form').forEach((form) => refreshPreferenceHighlights(form));
}

export function clearFilterForm(form) {
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

    form.querySelectorAll('select[data-select-enhanced]').forEach((select) => {
        window.jQuery?.(select).trigger('change.select2');
    });

    refreshPreferenceHighlights(form);
}
