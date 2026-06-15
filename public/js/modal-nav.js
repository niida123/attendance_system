/**
 * modal-nav.js
 * Keyboard navigation (Enter / ArrowDown / ArrowUp) for all modal form fields.
 */
(function ($) {
    'use strict';

    function handleFieldNav(e, selector) {
        if (e.key === 'Enter' || e.key === 'ArrowDown') {
            e.preventDefault();
            const inputs = $(e.target).closest('.modal-body').find(selector).filter(':visible:not(:disabled)');
            const idx    = inputs.index(e.target);
            if (idx < inputs.length - 1) inputs.eq(idx + 1).focus();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const inputs = $(e.target).closest('.modal-body').find(selector).filter(':visible:not(:disabled)');
            const idx    = inputs.index(e.target);
            if (idx > 0) inputs.eq(idx - 1).focus();
        }
    }

    $(document).on('keydown', '.modal-body .modal-input, .modal-body .form-control', function (e) {
        // Avoid hijacking textarea Enter key
        if (e.key === 'Enter' && $(this).is('textarea')) return;

        const selector = $(this).hasClass('modal-input') ? '.modal-input' : '.form-control';
        handleFieldNav(e, selector);
    });

})(jQuery);