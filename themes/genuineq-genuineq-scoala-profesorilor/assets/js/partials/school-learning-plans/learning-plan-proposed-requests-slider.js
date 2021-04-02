$( document ).ready(function() {
    let jsScript = $("#learning-plan-proposed-requests-slider");
    let id = jsScript.attr("data-id");

    $('#carousel-multiple-courses-' + id).on('slide.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 1;
        var totalItems = $('.carousel-item-' + id).length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item-' + id).eq(i).appendTo('.carousel-inner-' + id);
                } else {
                    $('.carousel-item-' + id).eq(0).appendTo('.carousel-inner-' + id);
                }
            }
        }
    });


    $('.proposed-requests-accept').on('click', function () {
        /* Extract the course and learning plan ID. */
        var $courseId = $(this).data('courseId');
        var $learningPlanId = $(this).data('learningPlanId');

        $.request(
            'onSchoolLearningPlanRequestAccept',
            {
                update: {'school-teachers/teacher-view': '#teachers-tab-content'},
                data: {
                    courseId: $courseId,
                    learningPlanId: $learningPlanId,
                },
            }
        );
    });


    $('.proposed-requests-decline').on('click', function () {
        /* Extract the course and learning plan ID. */
        var $courseId = $(this).data('courseId');
        var $learningPlanId = $(this).data('learningPlanId');

        $.request(
            'onSchoolLearningPlanRequestDecline',
            {
                update: {'school-teachers/teacher-view': '#teachers-tab-content'},
                data: {
                    courseId: $courseId,
                    learningPlanId: $learningPlanId,
                },
            }
        );
    });
});
