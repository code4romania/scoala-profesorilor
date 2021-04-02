$( document ).ready(function() {
    let jsScript = $("#image-crop");
    let avatarId = jsScript.attr("data-avatarId");
    let eventHandler = jsScript.attr("data-eventHandler");
    let schoolTeacherId = jsScript.attr("data-schoolTeacherId");

    /* Global variable for croppie instance. */
    window['imageCrop_' + avatarId] = null;

    /** Function that creates a croppie instance. */
    function createCoppieInstance() {
        window['imageCrop_' + avatarId] = $('#crop-image-' + avatarId).croppie({
            enableExif: true,
            enableZoom: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'circle'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });
    }

    /** Event listener for "change" event on file input field. */
    $('#' + avatarId).on('change', function() {
        var reader = new FileReader();

        if(this.files[0]){
            /* Create croppie instance. */
            createCoppieInstance();
        }

        reader.onload = function(event) {
            /* Bind image to croppie. */
            window['imageCrop_' + avatarId].croppie('bind', {
                url: event.target.result
            });
        }

        /* Open modal only when a file is selected. */
        if(this.files[0]){
            reader.readAsDataURL(this.files[0]);
            /* Display image crop modal. */
            $('#crop-image-modal-' + avatarId).modal('show');
        }
    });

    /** Event listener for "click" event on crop button. */
    $('.crop-image-btn-' + avatarId).click(function(event) {
        window['imageCrop_' + avatarId].croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response) {
            /* Close the crop image modal. */
            $('#crop-image-modal-' + avatarId).modal('hide');

            /* Destroy croppie instance. */
                $('#crop-image-' + avatarId).croppie('destroy');

            /* Send image to server. */
            if(schoolTeacherId) {
                $.request(
                    eventHandler,
                    {
                        data: { 'avatar': response, 'teacherId': schoolTeacherId }
                    }
                )
            } else {
                $.request(
                    eventHandler,
                    {
                        data: { 'avatar': response }
                    }
                )
            }
        });
    });
});
