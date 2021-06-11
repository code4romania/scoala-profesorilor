<?php return [
    'plugin' => [
        'name' => 'TMS',
        'description' => 'Contains all the models and controllers related to the TMS',

        'backend-menu' => [
            'dynamic' => 'TMS',
            'static' => 'TMS-Statics',
        ],
    ],

    'category' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'color' => 'Color',
            'icon' => 'Icon',
            'description' => 'Description',
        ],

        'backend-menu' => 'Categories',
    ],

    'skill' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
        ],

        'backend-menu' => 'Skills',
    ],

    'course' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'date-start' => 'Start Date',
            'date-end' => 'End Date',
            'accredited' => 'Accredited',
            'credits' => 'Credits',
            'address' => 'Address',
            'duration' => 'Duration (hours)',
            'price' => 'Price (RON)',
            'color' => 'Color',
            'description' => 'Description',
            'status_list' => 'Active(1)/Archived(0)',
            'status_form' => 'Status',
            'categories' => 'Categories',
            'skills' => 'Skills',
            'supplier' => 'Supplier',
            'supplier_name' => 'Supplier Name',
            'categories_names' => 'Categories Names',
            'skills_names' => 'Skills Names',
        ],

        'comment' => [
            'accredited' => 'Specifies if a course is accredited or not.',
        ],

        'backend-menu' => 'Courses',

        'backend' => [
            'archive' => 'Archive selected',
            'archive_confirm' => 'Archive the selected records?',
        ],

        'frontend' => [
            'all_courses' => 'Toate categoriile',
            'all_accreditations' => 'Toate acreditarile',
            'accredited' => 'Acreditat',
            'not_accredited' => 'Neacreditat',
            'name_asc' => 'Nume Ascendent',
            'name_desc' => 'Nume Descendent',
            'duration_asc' => 'Durată Ascendent',
            'duration_desc' => 'Durată Descendent',
            'start_date_asc' => 'Dată start Ascendent',
            'start_date_desc' => 'Dată start Descendent',
            'end_date_asc' => 'Dată finalizare Ascendent ',
            'end_date_desc' => 'Dată finalizare Descendent',
            'credits_asc' => 'Credite Ascendent',
            'credits_desc' => 'Credite Descendent',
            'price_asc' => 'Preț Ascendent',
            'price_desc' => 'Preț Descendent',
        ],

        'import-export' => [
            'supplier_not_found' => 'Supplier not found: ',
            'category_not_found' => 'Category not found: ',
            'skill_not_found' => 'Skill not found: ',
            'import_label' => 'Import',
            'export_label' => 'Export',
        ]
    ],

    'supplier' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'Email',
            'phone' => 'Phone',
            'description' => 'Description',
            'status_list' => 'Active(1)/Archived(0)',
            'status_form' => 'Status',
        ],

        'backend-menu' => 'Suppliers',
    ],

    'address' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'county' => 'County',
            'auto' => 'Auto',
            'zip' => 'Zip',
            'population' => 'Population',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ],

        'backend-menu' => 'Addresses',
    ],

    'teacher' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'seniority' => 'Seniority',
            'status' => 'Status',
            'birth_date' => 'Birth Date',
            'user' => 'User',
            'seniority_level' => 'Seniority Level',
            'description' => 'Description',
            'schools' => 'Schools',
        ],

        'backend-menu' => 'Teachers',

        'frontend' => [
            'all_seniority_levels' => 'Toate senioritatile',
            'all_school_levels' => 'Toate nivelele școlare',
            'all_contract_types' => 'Toate tipurile contractuale',
            'name_asc' => 'Nume Ascendent',
            'name_desc' => 'Nume Descendent',
            'birth_date_asc' => 'Dată naștere Ascendent',
            'birth_date_desc' => 'Dată naștere Descendent',
        ],
    ],

    'inspectorate' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Inspectorates',
    ],

    'school' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'phone' => 'Phone',
            'email' => 'Email',
            'principal' => 'Principal',
            'avatar' => 'Avatar',
            'description' => 'Description',
            'contact_name' => 'Contact Name',
            'contact_email' => 'Contact Email',
            'contact_phone' => 'Contact Phone',
            'contact_role' => 'Contact Role',
            'address' => 'Address',
            'detailed_address' => 'Detailed Address',
            'inspectorate' => 'Inspectorate',
            'status_list' => 'Active(1)/Archived(0)',
            'status' => 'Status',
            'user' => 'User',
            'teachers' => 'Teachers',
            'type' => 'Tip',
            'type_list' => 'Public/Privat',
        ],

        'backend-menu' => 'Schools',
    ],

    'learning-plan' => [
        'label' => [
            'id' => '#',
            'year' => 'Year',
            'semester' => 'Semester',
            'teacher' => 'Teacher',
            'courses' => 'Learning Plan Courses',
            'status_list' => 'Active(1)/Archived(0)',
        ],

        'backend-menu' => 'Learning Plans',
    ],

    'learning-plans-course' => [
        'label' => [
            'id' => '#',
            'learning_plan_name' => 'Teacher',
            'school_covered_costs' => 'School Covered Costs (RON)',
            'teacher_covered_costs' => 'Teacher Covered Costs (RON)',
            'mandatory' => 'Mandatory',
            'course_name' => 'Course',
        ],

        'backend-menu' => 'Learning Plans Courses',
    ],

    'specialization' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Specializations',
    ],

    'grade' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Grades',
    ],

    'seniority-level' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Seniority Levels',
    ],

    'taught-subjects' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Taught Subjects',
    ],

    'school-levels' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'School Levels',
    ],

    'contract-types' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Contract Types',
    ],

    'semesters' => [
        'label' => [
            'semester' => 'Semester',
            'year' => 'Year',
        ],

        'backend-menu' => 'Semesters',
    ],

    'appraisal' => [
        'label' => [
            'school' => 'School',
            'teacher' => 'Teacher',
            'semester' => 'Semester',
            'objectives' => 'Objectives',
            'status' => 'Status',
            'first_skill' => 'First skill',
            'first_skill_grade' => 'First skill grade',
            'first_skill_percentage' => 'First skill percentage',
            'first_skill_evaluation_type' => 'First skill evaluation type',
            'second_skill' => 'Second skill',
            'second_skill_grade' => 'Second skill grade',
            'second_skill_percentage' => 'Second skill percentage',
            'second_skill_evaluation_type' => 'Second skill evaluation type',
            'third_skill' => 'Third skill',
            'third_skill_grade' => 'Third skill grade',
            'third_skill_percentage' => 'Third skill percentage',
            'third_skill_evaluation_type' => 'Third skill evaluation type',
            'notes_objectives_set' => 'Notes objectives set',
            'notes_objectives_approved' => 'Notes objectives approved',
            'notes_skills_set' => 'Notes skills set',
            'notes_evaluation_opened' => 'Notes evaluation opened',
        ],

        'backend-menu' => 'Appraisals',

        'frontend' => [
            'all_statuses' => 'Toate stările',
            'all_years' => 'Toți anii',
            'all_schools' => 'Toate școlile',
            'all_semesters' => 'Toate semestrele',
            'new' => 'Nou',
            'objectives_set' => 'Obiective setate',
            'objectives_approved' => "Obiective aprobate",
            'skills_set' => 'Skill-uri alese',
            'evaluation_opened' => 'Evaluare deschisă',
            'closed' => 'Închis',
            'asc' => 'Crescător',
            'desc' => 'Descrescător',
            'grade' => 'Notă',
            'percentage' => 'Procent de finalizare',
        ],
    ],

    'budgets' => [
        'label' => [
            'budget' => 'Budget',
            'budgetable_name' => 'Budget owner',
            'budgetable_type' => 'Budget owner type',
            'semester' => 'Semester',
            'status_list' => 'Active(1)/Archived(0)',
            'status_form' => 'Status',
        ],

        'backend-menu' => 'Budgets',

        'frontend' => [
            'all_years' => 'Toți anii',
            'all_semesters' => 'Toate semestrele',
            'desc' => 'Cost acoperit descrescător',
            'asc' => 'Cost acoperit crescător',
        ],
    ],

    'component' => [
        'school-profile' => [
            'name' => 'School Profile',
            'description' => 'Allows the update of a school profile',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
            ],
            'validation' => [
                'name_regex' => 'Numele școlii poate conține doar litere, spațiu și caracterul -',
                'name_max' => 'Numele școlii trebuie să fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numărul de telefon al școlii poate fi format doar din numere',
                'phone_max' => 'Lungimea maximă a numărului de telefon al școlii este de 15 caractere',
                'principal_regex' => 'Numele directorului poate conține doar litere, spațiu și caracterul -',
                'principal_max' => 'Numele directorului trebuie să fie de maxim 50 de caractere lungime',
                'contact_name_regex' => 'Numele persoanei de contact poate conține doar litere, spațiu și caracterul -',
                'contact_name_max' => 'Numele persoanei de contact trebuie să fie de maxim 50 de caractere lungime',
                'contact_phone_numeric' => 'Numărul de telefon al persoanei de contact poate fi format doar din numere',
                'contact_phone_max' => 'Lungimea maximă a numărului de telefon al persoanei de contact este de 15 caractere',
                'contact_role_string' => 'Rolul persoanei de contact trebuie să fie de tip string',
                'contact_role_max' => 'Lungimea maximă a rolului persoanei de contact este de 50 de caractere',
                'contact_email_string' => 'Adresa de email a persoanei de contact trebuie să fie de tip string',
                'contact_email_max' => 'Lungimea maximă a adresei de email a persoanei de contact este de 50 de caractere',
                'contact_email_email' => 'Adresa de email a persoanei de contact nu este valida',
                'address_id_format' => 'Formatul adresei este invalid',
                'address_id_invalid' => 'Adresa specificată nu există în lista de adrese posibile',
                'address_id_numeric' => 'Adresa specificată nu există în lista de adrese posibile',
                'inspectorate_id_invalid' => 'Inspectoratul specificat nu există în lista de inspectorate posibile',
                'inspectorate_id_numeric' => 'Inspectoratul specificat nu există în lista de inspectorate posibile',
                'description_string' => 'Descrierea trebuie să fie de tip string',
                'detailed_address_string' => 'Adresa detaliată trebuie să fie de tip string',
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a eșuat',
                'description_update_successful' => 'Descrierea a fost actualizată cu succes',
                'description_update_failed' => 'Actualizarea descrierii a eșuat',
                'detailed_address_update_successful' => 'Adresa detaliată a fost actualizată cu succes',
                'detailed_address_update_failed' => 'Actualizarea adresei detaliate a eșuat',
                'login_required' => 'Trebuie să fiți autentificat',
                'delete_successful' => 'Contul a fost șters cu succes',
            ],
        ],

        'teacher-profile' => [
            'name' => 'Teacher Profile',
            'description' => 'Allows the update of a teacher profile',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
            ],
            'validation' => [
                'name_regex' => 'Numele profesorului poate conține doar litere, spațiu și caracterul -',
                'name_max' => 'Numele profesorului trebuie să fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numărul de telefon al profesorului poate fi format doar din numere',
                'phone_max' => 'Lungimea maximă a numărului de telefon al profesorului este de 15 caractere',
                'birth_date_date' => 'Dată specificată nu este formatată corect, folosiți formatul zz/ll/aaaa',
                'address_id_format' => 'Formatul adresei este invalid',
                'address_id_invalid' => 'Adresa specificată nu există în lista de adrese posibile',
                'address_id_numeric' => 'Adresa specificată nu există în lista de adrese posibile',
                'seniority_level_id_invalid' => 'Nivelul de senioritate specificat nu există în lista de valori posibile',
                'seniority_level_id_numeric' => 'Nivelul de senioritate specificat nu există în lista de valori posibile',
                'school_level_id_numeric' => 'Nivelul școlii specificat nu există în lista de valori posibile',
                'contract_type_id_numeric' => 'Tipul de contract specificat nu există în lista de valori posibile',
                'description_string' => 'Descrierea trebuie să fie de tip string',
                'invalid_budget' => 'Bugetul trebuie să fie minim 0',
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a eșuat',
                'description_update_successful' => 'Descrierea a fost actualizată cu succes',
                'budget_update_successful' => 'Bugetul a fost actualizat cu succes',
                'delete_successful' => 'Contul a fost șters cu succes',
                'description_update_failed' => 'Actualizarea descrierii a eșuat',
                'login_required' => 'Trebuie să fiți autentificat',
            ],
        ],

        'school-teacher-profile' => [
            'name' => 'School Teacher Profile',
            'description' => 'Allows the update of a teacher profile',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
            ],
            'validation' => [
                'name_regex' => 'Numele profesorului poate conține doar litere, numere, spațiu și caracterul -',
                'name_max' => 'Numele profesorului trebuie să fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numărul de telefon al profesorului poate fi format doar din numere',
                'phone_max' => 'Lungimea maximă a numărului de telefon al profesorului este de 15 caractere',
                'birth_date_date' => 'Dată specificată nu este formatată corect, folosiți formatul zz/ll/aaaa',
                'address_id_format' => 'Formatul adresei este invalid',
                'address_id_invalid' => 'Adresa specificată nu există în lista de adrese posibile',
                'address_id_numeric' => 'Adresa specificată nu există în lista de adrese posibile',
                'seniority_level_id_invalid' => 'Nivelul de senioritate specificat nu există în lista de valori posibile',
                'seniority_level_id_numeric' => 'Nivelul de senioritate specificat nu există în lista de valori posibile',
                'school_level_id_numeric' => 'Nivelul școlii specificat nu există în lista de valori posibile',
                'contract_type_id_numeric' => 'Tipul de contract specificat nu există în lista de valori posibile',
                'description_string' => 'Descrierea trebuie să fie de tip string',
                'account_mail_required' => 'Adresa de email este obligatorie',
                'account_mail_between' => 'Adresa de email trebuie să aibă între 6 și 255 de caractere',
                'account_mail_email' => 'Adresa de email nu este validă',
                'account_mail_unique' => 'Adresa de email este deja folosită',
                'account_new_password_required' => 'Nouă parolă este obligatorie',
                'account_new_password_between_s' => 'Nouă parolă trebuie să aibă între ',
                'account_new_password_between_e' => ' caractere',
                'account_new_password_confirmed' => 'Parolele nu se potrivesc',
                'account_new_password_confirmation_required' => 'Confirmarea noii parole este obligatorie',
                'account_new_password_confirmation_required_with' => 'Confirmarea noii parole este obligatorie',
                'school_level_1_id_invalid' => 'Nivelul Școlar specificat nu există în lista de valori posibile',
                'school_level_2_id_invalid' => 'Nivelul Școlar specificat nu există în lista de valori posibile',
                'school_level_3_id_invalid' => 'Nivelul Școlar specificat nu există în lista de valori posibile',
                'contract_type_id_invalid' => 'Tipul de colaborare specificat nu există în lista de valori posibile',
                'grade_id_invalid' => 'Notă specificată nu există în lista de valori posibile',
                'specialization_1_id_invalid' => 'Specializarea specificată nu există în lista de valori posibile',
                'specialization_2_id_invalid' => 'Specializarea specificată nu există în lista de valori posibile',

                'name_required' => 'Numele este obligatoriu',
                'name_regex' => 'Numele poate conține doar litere, spațiu și caracterul -',
                'email_required' => 'Adresa de email este obligatorie',
                'email_between' => 'Adresa de email trebuie să aibă între 6 și 255 de caractere',
                'email_email' => 'Adresa de email nu este validă',
                'email_unique' => 'Adresa de email este deja folosită',
                'identifier_required' => 'CNP-ul este obligatoriu pentru identificare unică',
                'identifier_unique' => 'CNP-ul introdus este deja folosit',
                'identifier_invalid' => 'CNP-ul introdus nu este valid',
            ],
            'message' => [
                'teacher_not_linked' => 'Nu puteți modifică un profesor neasociat',
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a eșuat',
                'description_update_successful' => 'Descrierea a fost actualizată cu succes',
                'description_update_failed' => 'Actualizarea descrierii a eșuat',
                'login_required' => 'Trebuie să fiți autentificat',
                'avatar_update_successful' => 'Poză de profil a fost actualizată cu succes',
                'avatar_update_failed' => 'Actualizarea pozei de profil a eșuat',
                'email_update_successful' => 'Adresa de email a fost actualizată cu succes',
                'password_update_successful' => 'Parolă a fost actualizată cu succes',
                'teachers_import_successful_1' => ' profesori au fost adăugați cu succes și pentru ',
                'teachers_import_successful_2' => ' au apărut erori',
                'teachers_import_failed' => 'Nu a fost specificat un fișier.',
                'teachers_import_sheet_skipped' => 'Sheet-ul următor nu a fost parcurs: ',
                'teachers_import_failed' => 'Import-ul a eșuat.',
                'teachers_import_association_successful' => 'Profesorul a fost asociat cu succes',
                'teachers_import_association_exists' => 'Profesorul este deja asociat',
                'teachers_import_add_successful' => 'Profesorul a fost adăugat cu succes',
                'not_exists' => 'Profesorul specificat nu există sau nu sunteți asociat cu el',
            ],
        ],

        'course-search' => [
            'name' => 'Course Search',
            'description' => 'Allows the search, filter, order and paginate courses',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
                'category' => 'Category param',
                'category_desc' => 'The URL parameter from which to extract the category from which to filter',
            ],
        ],

        'learning-plan' => [
            'name' => 'Learning Plan',
            'description' => 'Allows to view and update a learning plan',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
            ],
            'validation' => [
                'learning_plan_id_required' => 'Nu a fost selectat un plan de învățare',
                'learning_plan_id_numeric' => 'Planul de învățare selectat nu este corect',
                'learning_plan_id_exists' => 'Planul de învățare selectat nu există',
                'course_id_required' => 'Nu a fost selectat un curs',
                'course_id_numeric' => 'Cursul selectat nu este corect',
                'course_id_exists' => 'Cursul selectat nu există',
                'school_budget_id_required' => 'Nu a fost selectată o școală',
                'school_budget_id_numeric' => 'Școală selectată nu este corectă',
                'school_budget_id_exists' => 'Școală selectată nu există',
                'school_covered_costs_present' => 'Costurile decontate trebuie să fie trimise',
                'school_covered_costs_numeric' => 'Costurile decontate trebuie să fie numerice',
                'school_covered_costs_max' => 'Costurile decontate nu pot să fie mai mari decât valoarea cursului',
            ],
            'message' => [
                'course_added_successful' => 'Cursul a fost adăugat cu succes',
                'request_added_successful' => 'Cerere de curs creată cu succes',
                'course_deleted_successful' => 'Cursul a fost șters cu succes',
                'course_accepted_successful' => 'Cererea a fost acceptată cu succes',
                'course_declined_successful' => 'Cererea a fost refuzată cu succes',
                'participation_updated_successful' => 'Participarea la curs a fost actualizată cu succes',
                'school_course_deleted_not_allowed' => 'Nu puteți să ștergeți un curs adăugat de către o altă școală',
                'school_not_valid' => 'Școală selectată nu există sau nu sunteți asociat cu ea',
                'course_deleted_not_allowed' => 'Nu puteți să ștergeți un curs marcat că obligatoriu de către o școală',
            ],
        ],

        'appraisal' => [
            'name' => 'Appraisal',
            'description' => 'Allows to view and update an appraisal',
            'validation' => [
            ],
            'message' => [
                'not_allowed' => 'Nu aveți acces la evaluarea cerută',
                'not_exists' => 'Evaluarea cerută nu există',
                'empty_objectives' => 'Obiectivele nu pot să fie goale',
                'save_successful' => "Schimbările au fost salvate cu succes",
                'objectives_set_successful' => "Obiectivele au fost stabilite cu succes",
                'objectives_approved_successful' => "Obiectivele au fost aprobate cu succes",
                'skills_set_successful' => "Skill-urile au fost stabilite cu succes",
                'close_successful' => "Evaluarea a fost închisă cu succes",
                'invalid_grade' => "Notele pot să fie între 1 și 10 iar procentele între 1 și 100",
                'teacher_not_exists' => 'Profesorul specificat nu există sau nu sunteți asociat cu el',
                'wrong_percentage' => 'Suma ponderilor trebuie să fie egală cu 100',
            ],
        ],

        'budget' => [
            'name' => 'Budget',
            'description' => 'Allows the search, filter, order and paginate budget courses',
            'validation' => [
            ],
        ],

        'school-dashboard' => [
            'name' => 'School Dashboard',
            'description' => 'Displays the school dashboard',
            'backend' => [
                'force_secure' => 'Force secure protocol',
                'force_secure_desc' => 'Always redirect the URL with the HTTPS schema',
            ],

            'frontend' => [
                'budget_total' => 'Buget total RON',
                'budget_spent' => 'Buget cheltuit RON',
                'teachers_not_financed' => 'Profesori nefinantati',
                'teachers_financed' => 'Profesori finanțați',
                'accredited_courses' => 'Cursuri acreditate',
                'noncredited_courses' => 'Cursuri neacreditate',
                'school_spent_money' => 'Costuri școală RON',
                'teachers_spent_money' => 'Costuri profesori RON',
                'school_budget' => 'Buget total școală RON',
                'teachers_budget' => 'Buget total profesori RON',
                'school_paid_courses' => 'Cursuri plătite de școală',
                'teachers_paid_courses' => 'Cursuri plătite de profesori',
                'first_semester' => ['0' => 'August', '1' => 'Septembrie', '2' => 'Octombrie', '3' => 'Noiembrie', '4' => 'Decembrie', '5' => 'Ianuarie'],
                'second_semester' => ['0' => 'Februarie', '1' => 'Martie', '2' => 'Aprilie', '3' => 'Mai', '4' => 'Iunie', '5' => 'Iulie'],
                'compare_semester' => ['0' => 'Luna 1', '1' => 'Luna 2', '2' => 'Luna 3', '3' => 'Luna 4', '4' => 'Luna 5', '5' => 'Luna 6'],
                'first_semester_distributed_costs_label' => 'Semestrul 1 ',
                'second_semester_distributed_costs_label' => 'Semestru 2 ',

                'datatable_name' => 'Nume',
                'datatable_identifier' => 'CNP',
                'datatable_birthdate' => 'Dată Naștere',
                'datatable_email' => 'Email',
                'datatable_phone' => 'Telefon',
                'datatable_birth_date' => 'Dată Naștere',
                'datatable_description' => 'Descriere',
                'datatable_address' => 'Adresa',
                'datatable_seniority' => 'Senioritate',
                'datatable_school_level_1' => 'Nivel Școlar 1',
                'datatable_school_level_2' => 'Nivel Școlar 2',
                'datatable_school_level_3' => 'Nivel Școlar 3',
                'datatable_contract_type' => 'Tip Contract',
                'datatable_grade' => 'Grad',
                'datatable_specialization_1' => 'Specializare 1',
                'datatable_specialization_2' => 'Specializare 2',
                'datatable_skill_1' => 'Competență 1 (C1)',
                'datatable_skill_grade_1' => 'Notă C1',
                'datatable_skill_percentage_1' => 'Procent C1',
                'datatable_evaluation_type_1' => 'Metodă evaluare C1',
                'datatable_skill_2' => 'Competență 2 (C2)',
                'datatable_skill_grade_2' => 'Notă C2',
                'datatable_skill_percentage_2' => 'Procent C2',
                'datatable_evaluation_type_2' => 'Metodă evaluare C2',
                'datatable_skill_3' => 'Competență 3 (C3)',
                'datatable_skill_grade_3' => 'Notă C3',
                'datatable_skill_percentage_3' => 'Procent C3',
                'datatable_evaluation_type_3' => 'Metodă evaluare C3',
                'datatable_skill_grades_average' => 'Medie competențe',
            ],

            'validation' => [
                'invalid_budget' => 'Bugetul trebuie să fie minim 0',
            ],

            'message' => [
                'login_required' => 'Trebuie să fiți autentificat',
                'budget_update_successful' => 'Bugetul a fost actualizat cu succes',
            ],
        ],
    ],

    'reportwidgets' => [
        'totalcourses' => [
            'label' => 'Total Courses',
            'title' => 'Widget title',
            'title_default' => 'Total Courses',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_courses' => 'Courses',
            ]
        ],

        'totalschools' => [
            'label' => 'Total Schools',
            'title' => 'Widget title',
            'title_default' => 'Total Schools',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_school' => 'Schools',
            ]
        ],

        'totalskills' => [
            'label' => 'Total Skills',
            'title' => 'Widget title',
            'title_default' => 'Total Skills',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_skills' => 'Skills',
            ]
        ],

        'totalsuppliers' => [
            'label' => 'Total Suppliers',
            'title' => 'Widget title',
            'title_default' => 'Total Suppliers',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_suppliers' => 'Suppliers',
            ]
        ],

        'totalteachers' => [
            'label' => 'Total Teachers',
            'title' => 'Widget title',
            'title_default' => 'Total Teachers',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_teachers' => 'Teachers',
            ]
        ],

        'learningplancompletion' => [
            'label' => 'Learning Plans Status',
            'title' => 'Widget title',
            'title_default' => 'Learning Plans Status',
            'title_validation' => 'Title is required',

            'frontend' => [
                'label_learning_plans_total' => 'Learning Plans Total',
                'label_learning_plans_completed' => 'Learning Plans Completed',
                'label_learning_plans_completed_percentage' => 'Learning Plans Completed Percentage',
            ]
        ],

        'coursestop' => [
            'label' => 'Courses Top',
            'title' => 'Widget title',
            'title_default' => 'Courses Top',
            'title_validation' => 'Title is required',
        ],
    ],
];
