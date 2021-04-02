$( document ).ready(function() {
    let jsScript = $("#teacher-add-modal");
    let tt1 = jsScript.attr("data-schoolTeacherAddresses")
    let tt2 = jsScript.attr("data-schoolTeacherSeniorityLevels")
    let tt3 = jsScript.attr("data-schoolTeacherSchoolLevels")
    console.log(tt1);
    console.log(tt2);
    console.log(tt3);
    let schoolTeacherAddresses = JSON.parse(jsScript.attr("data-schoolTeacherAddresses"));
    let schoolTeacherSeniorityLevels = JSON.parse(jsScript.attr("data-schoolTeacherSeniorityLevels"));
    let schoolTeacherSchoolLevels = JSON.parse(jsScript.attr("data-schoolTeacherSchoolLevels"));
    let schoolTeacherContractTypes = JSON.parse(jsScript.attr("data-schoolTeacherContractTypes"));
    let schoolTeacherGrades = JSON.parse(jsScript.attr("data-schoolTeacherGrades"));
    let schoolTeacherSpecializations = JSON.parse(jsScript.attr("data-schoolTeacherSpecializations"));

    console.log(schoolTeacherAddresses);
    console.log(schoolTeacherSeniorityLevels);
    console.log(schoolTeacherContractTypes);

    $('#teachers-tab-content').on('ajaxUpdate', function() {
        /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
        $('input.form-control').change();
        $('textarea.form-control').change();
        $('input.custom-file-input').change();
    })

    $('#school_teacher_add_address_id').autocomplete({
        source: schoolTeacherAddresses,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_seniority_level_id').autocomplete({
        source: schoolTeacherSeniorityLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_school_level_1_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_school_level_2_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_school_level_3_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_contract_type_id').autocomplete({
        source: schoolTeacherContractTypes,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_grade_id').autocomplete({
        source: schoolTeacherGrades,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_specialization_1_id').autocomplete({
        source: schoolTeacherSpecializations,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_specialization_2_id').autocomplete({
        source: schoolTeacherSpecializations,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_add_birth_date').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        language: "ro",
        todayHighlight: true
    });

    $('#schoolTeacherAdd').on('submit', function () {
        /* Close the teacher add modal. */
        $('#teacher-add-modal').modal('hide');

        /* Initiate the teacher add. */
        $('#schoolTeacherAdd').request(
            'onTeacherAdd',
            {
                update: {'school-profile/teachers-tab': '#teachers-tab-content'},
            }
        );

        return false;
    });

    $("#teacher-add-modal").on('shown.bs.modal', function(){
        /* Hide the body scrollbar. */
        document.body.style.overflow = "hidden";
    });

    $("#teacher-add-modal").on('hidden.bs.modal', function(){
        /* Show the body scrollbar. */
        document.body.style.overflow = "auto";
    });
});
