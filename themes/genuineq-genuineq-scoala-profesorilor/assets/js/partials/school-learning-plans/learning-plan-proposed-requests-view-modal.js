$( document ).ready(function() {
    /* Function populated the modal with the data to diplay. */
    $('#learning-plan-proposed-requests-view-modal').on('show.bs.modal', function (event) {
        /* Set course-id and learning-plan-id data attributes. */
        $('#btnDeclineRequest').data('courseId', $(event.relatedTarget).data('courseId'));
        $('#btnDeclineRequest').data('learningPlanId', $(event.relatedTarget).data('learningPlanId'));

        /* Set course-id and learning-plan-id data attributes. */
        $('#btnAcceptRequest').data('courseId', $(event.relatedTarget).data('courseId'));
        $('#btnAcceptRequest').data('learningPlanId', $(event.relatedTarget).data('learningPlanId'));

        /* Set input values. */
        $('#school_covered_costs').val($(event.relatedTarget).data('schoolCoveredCosts'));
        $('#teacher_covered_costs').val($(event.relatedTarget).data('teacherCoveredCosts'));

        /* Set checkbox values. */
        if ($(event.relatedTarget).data('transportCovered')) {
            $('#transport_covered').prop('checked', true);
        }
        if ($(event.relatedTarget).data('accommodationCovered')) {
            $('#accommodation_covered').prop('checked', true);
        }

        /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
        $('#school_covered_costs').change();
        $('#teacher_covered_costs').change();
    });
});
