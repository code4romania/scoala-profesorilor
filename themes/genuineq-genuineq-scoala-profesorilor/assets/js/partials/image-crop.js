
$( document ).ready(function() {
    /* Global variable for croppie instance. */
    var $imageCrop_{{ avatarId }} = null;

    /** Function that creates a croppie instance. */
    function createCoppieInstance() {
        $imageCrop_{{ avatarId }} = $('#crop-image-{{ avatarId }}').croppie({
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
    $('#{{ avatarId }}').on('change', function() {
        var reader = new FileReader();

        if(this.files[0]){
            /* Create croppie instance. */
            createCoppieInstance();
        }

        reader.onload = function(event) {
            /* Bind image to croppie. */
            $imageCrop_{{ avatarId }}.croppie('bind', {
                url: event.target.result
            });
        }

        /* Open modal only when a file is selected. */
        if(this.files[0]){
            reader.readAsDataURL(this.files[0]);
            /* Display image crop modal. */
            $('#crop-image-modal-{{ avatarId }}').modal('show');
        }
    });

    /** Event listener for "click" event on crop button. */
    $('.crop-image-btn-{{ avatarId }}').click(function(event) {
        $imageCrop_{{ avatarId }}.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function(response) {
            /* Close the crop image modal. */
            $('#crop-image-modal-{{ avatarId }}').modal('hide');

            /* Destroy croppie instance. */
                $('#crop-image-{{ avatarId }}').croppie('destroy');

            /* Send image to server. */
            $.request(
                '{{ eventHandler }}',
                {
                    {% if schoolTeacherId %}
                        data: { 'avatar': response, 'teacherId': {{ schoolTeacherId }} }
                    {% else %}
                        data: { 'avatar': response }
                    {% endif %}
                }
            );
        });
    });
});
