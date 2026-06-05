export function refreshSelect2(form) {
    form.querySelectorAll('select[data-select-enhanced]').forEach((select) => {
        window.jQuery?.(select).trigger('change.select2');
    });
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
}

export function initAgeRangeSliders(root = document) {
    if (!window.jQuery?.fn?.slider) return;

    root.querySelectorAll('[data-age-range-slider]').forEach((slider) => {
        const form = slider.closest('form');
        const minInput = form?.querySelector('[data-age-range-min]');
        const maxInput = form?.querySelector('[data-age-range-max]');
        const label = form?.querySelector('[data-age-range-label]');
        if (!form || !minInput || !maxInput || !label) return;

        const min = Number(slider.dataset.min || 3);
        const max = Number(slider.dataset.max || 18);
        const selectedMin = Number(minInput.value || slider.dataset.selectedMin || min);
        const selectedMax = Number(maxInput.value || slider.dataset.selectedMax || max);
        const $slider = window.jQuery(slider);
        const updateRange = (values) => {
            minInput.value = values[0];
            maxInput.value = values[1];
            label.textContent = `${values[0]} - ${values[1]}`;
        };

        if ($slider.hasClass('ui-slider')) {
            $slider.slider('values', [selectedMin, selectedMax]);
            updateRange([selectedMin, selectedMax]);
            return;
        }

        $slider.slider({
            range: true,
            min,
            max,
            values: [selectedMin, selectedMax],
            slide: (_event, ui) => updateRange(ui.values),
            change: (_event, ui) => updateRange(ui.values),
            stop: (_event, ui) => {
                updateRange(ui.values);
                slider.dispatchEvent(new Event('change', { bubbles: true }));
            },
        });
    });
}
