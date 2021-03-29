/** Recaptcha V3 functions */
function getTokenSupport() {
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ site_key }}", {action: "{{ recaptcha.property('action_name') }}"})
            .then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
            });
    });
}

function getRecaptchaToken() {
    getTokenSupport();

    /**
     * Get recaptcha token once on page load, then reload it every two
     * minutes to prevent it from timing out while the user fills out
     * the form.
     */

    getTokenSupport();
    window.setInterval(getRecaptchaToken, 120000);
}

/** recaptchaV2 function */
function submitRecaptchaForm() {
    return new Promise(function(resolve, reject) {
        var form = $("#recaptcha_{{recaptcha.id}}").closest("form");
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
        }
        else {
            form.submit();
        }
        grecaptcha.reset()
    });
}


$(document).ready( () => {
    /** Modal functions */
    $("#login-modal").on('shown.bs.modal', function(){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#login-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
})
