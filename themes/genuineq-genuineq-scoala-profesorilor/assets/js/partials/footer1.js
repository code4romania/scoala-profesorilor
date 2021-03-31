$(document).on('ajaxSetup', function(event, context) {
    // Enable AJAX handling of Flash messages on all AJAX requests
    context.options.flash = true

    // Enable the StripeLoadIndicator on all AJAX requests
    context.options.loading = $.oc.stripeLoadIndicator

    // Handle Error Messages by triggering a flashMsg of type error
    context.options.handleErrorMessage = function(message) {
        $.oc.flashMsg({ text: message, class: 'error' })
    }

    // Handle Flash Messages by triggering a flashMsg of the message type
    context.options.handleFlashMessage = function(message, type) {
        $.oc.flashMsg({ text: message, class: type })
    }
})
