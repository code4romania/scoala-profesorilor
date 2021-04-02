$( document ).ready(function() {
    let jsScript = $("#teacher-edit");
    let schoolTeacherAddresses = JSON.parse(jsScript.attr("data-schoolTeacherAddresses"));
    let schoolTeacherSeniorityLevels = JSON.parse(jsScript.attr("data-schoolTeacherSeniorityLevels"));
    let schoolTeacherSchoolLevels = JSON.parse(jsScript.attr("data-schoolTeacherSchoolLevels"));
    let schoolTeacherContractTypes = JSON.parse(jsScript.attr("data-schoolTeacherContractTypes"));
    let schoolTeacherGrades = JSON.parse(jsScript.attr("data-schoolTeacherGrades"));
    let schoolTeacherSpecializations = JSON.parse(jsScript.attr("data-schoolTeacherSpecializations"));

    $('#teachers-tab-content').on('ajaxUpdate', function() {
        /* MDBOOTSTRAP Limitation: Trigger change value to move all input labels up. */
        $('input.form-control').change();
        $('textarea.form-control').change();
        $('input.custom-file-input').change();
    })

    $('#school_teacher_address_id').autocomplete({
        source: schoolTeacherAddresses,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_seniority_level_id').autocomplete({
        source: schoolTeacherSeniorityLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_school_level_1_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_school_level_2_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_school_level_3_id').autocomplete({
        source: schoolTeacherSchoolLevels,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_contract_type_id').autocomplete({
        source: schoolTeacherContractTypes,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_grade_id').autocomplete({
        source: schoolTeacherGrades,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_specialization_1_id').autocomplete({
        source: schoolTeacherSpecializations,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_specialization_2_id').autocomplete({
        source: schoolTeacherSpecializations,
        highlightClass: 'text-danger',
        treshold: 1
    });

    $('#school_teacher_birth_date').datepicker({
        format: "dd-mm-yyyy",
        weekStart: 1,
        language: "ro",
        todayHighlight: true
    });

    $('#school_teacher_avatar').on('change',function(event){
        if (event.target.files.length) {
            $(this).next('.custom-file-label').html(event.target.files[0].name);
        }
    })
});
