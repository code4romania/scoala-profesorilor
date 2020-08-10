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
            'duration_asc' => 'Durata Ascendent',
            'duration_desc' => 'Durata Descendent',
            'start_date_asc' => 'Data start Ascendent',
            'start_date_desc' => 'Data start Descendent',
            'end_date_asc' => 'Data finalizare Ascendent ',
            'end_date_desc' => 'Data finalizare Descendent',
            'credits_asc' => 'Credite Ascendent',
            'credits_desc' => 'Credite Descendent',
            'price_asc' => 'Pret Ascendent',
            'price_desc' => 'Pret Descendent',
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
            'all_school_levels' => 'Toate nivelele scolare',
            'all_contract_types' => 'Toate tipurile contractuale',
            'name_asc' => 'Nume Ascendent',
            'name_desc' => 'Nume Descendent',
            'birth_date_asc' => 'Data nastere Ascendent',
            'birth_date_desc' => 'Data nastere Descendent',
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
            'second_skill' => 'Second skill',
            'second_skill_grade' => 'Second skill grade',
            'second_skill_percentage' => 'Second skill percentage',
            'third_skill' => 'Third skill',
            'third_skill_grade' => 'Third skill grade',
            'third_skill_percentage' => 'Third skill percentage',
            'notes_objectives_set' => 'Notes objectives set',
            'notes_objectives_approved' => 'Notes objectives approved',
            'notes_skills_set' => 'Notes skills set',
            'notes_evaluation_opened' => 'Notes evaluation opened',
        ],

        'backend-menu' => 'Appraisals',

        'frontend' => [
            'all_statuses' => 'Toate starile',
            'all_years' => 'Toti anii',
            'all_schools' => 'Toate scolile',
            'all_semesters' => 'Toate semestrele',
            'new' => 'Nou',
            'objectives_set' => 'Obiective setate',
            'objectives_approved' => "Obiective aprobate",
            'skills_set' => 'Skill-uri alese',
            'evaluation_opened' => 'Evaluare deschisa',
            'closed' => 'Inchis',
            'asc' => 'Crescator',
            'desc' => 'Descrescator',
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
            'all_years' => 'Toti anii',
            'all_semesters' => 'Toate semestrele',
            'desc' => 'Cost acoperit descrescator',
            'asc' => 'Cost acoperit crescator',
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
                'name_regex' => 'Numele scolii poate contine doar litere, spatiu si caracterul -',
                'name_max' => 'Numele scolii trebuie sa fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numarul de telefon al scolii poate fi format doar din numere',
                'phone_max' => 'Lungimea maxima a numarului de telefon al scolii este de 15 caractere',
                'email_string' => 'Adresa de email a scolii trebuie sa fie de tip string',
                'email_max' => 'Lungimea maxima a adresei de email a scolii este de 50 de caractere',
                'email_email' => 'Adresa de email a scolii nu este valida',
                'principal_regex' => 'Numele directorului poate contine doar litere, spatiu si caracterul -',
                'principal_max' => 'Numele directorului trebuie sa fie de maxim 50 de caractere lungime',
                'contact_name_regex' => 'Numele persoanei de contact poate contine doar litere, spatiu si caracterul -',
                'contact_name_max' => 'Numele persoanei de contact trebuie sa fie de maxim 50 de caractere lungime',
                'contact_phone_numeric' => 'Numarul de telefon al persoanei de contact poate fi format doar din numere',
                'contact_phone_max' => 'Lungimea maxima a numarului de telefon al persoanei de contact este de 15 caractere',
                'contact_role_string' => 'Rolul persoanei de contact trebuie sa fie de tip string',
                'contact_role_max' => 'Lungimea maxima a rolului persoanei de contact este de 50 de caractere',
                'contact_email_string' => 'Adresa de email a persoanei de contact trebuie sa fie de tip string',
                'contact_email_max' => 'Lungimea maxima a adresei de email a persoanei de contact este de 50 de caractere',
                'contact_email_email' => 'Adresa de email a persoanei de contact nu este valida',
                'address_id_numeric' => 'Adresa specificata nu exista in lista de adrese posibile',
                'inspectorate_id_numeric' => 'Inspectoratul specificat nu exista in lista de inspectorate posibile',
                'description_string' => 'Descrierea trebuie sa fie de tip string',
                'detailed_address_string' => 'Adresa detaliata trebuie sa fie de tip string',
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a esuat',
                'description_update_successful' => 'Descrierea a fost actualizata cu succes',
                'description_update_failed' => 'Actualizarea descrierii a esuat',
                'detailed_address_update_successful' => 'Adresa detaliata a fost actualizata cu succes',
                'detailed_address_update_failed' => 'Actualizarea adresei detaliate a esuat',
                'login_required' => 'Trebuie sa fiti autentificat',
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
                'name_regex' => 'Numele profesorului poate contine doar litere, spatiu si caracterul -',
                'name_max' => 'Numele profesorului trebuie sa fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numarul de telefon al profesorului poate fi format doar din numere',
                'phone_max' => 'Lungimea maxima a numarului de telefon al profesorului este de 15 caractere',
                'birth_date_date' => 'Data specificata nu este formatata corect. Folosit formatul dd/mm/yyyy',
                'address_id_numeric' => 'Adresa specificata nu exista in lista de adrese posibile',
                'seniority_level_id_numeric' => 'Nivelul de senioritate specificat nu exista in lista de valori posibile',
                'school_level_id_numeric' => 'Nivelul scolii specificat nu exista in lista de valori posibile',
                'contract_type_id_numeric' => 'Tipul de contract specificat nu exista in lista de valori posibile',
                'description_string' => 'Descrierea trebuie sa fie de tip string',
                'invalid_budget' => 'Bugetul trebuie sa minim 0'
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a esuat',
                'description_update_successful' => 'Descrierea a fost actualizata cu succes',
                'budget_update_successful' => 'Bugetul a fost actualizat cu succes',
                'description_update_failed' => 'Actualizarea descrierii a esuat',
                'login_required' => 'Trebuie sa fiti autentificat',
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
                'name_regex' => 'Numele profesorului poate contine doar litere, numere, spatiu si caracterul -',
                'name_max' => 'Numele profesorului trebuie sa fie de maxim 50 de caractere lungime',
                'phone_regex' => 'Numarul de telefon al profesorului poate fi format doar din numere',
                'phone_max' => 'Lungimea maxima a numarului de telefon al profesorului este de 15 caractere',
                'birth_date_date' => 'Data specificata nu este formatata corect. Folosit formatul dd/mm/yyyy',
                'address_id_numeric' => 'Adresa specificata nu exista in lista de adrese posibile',
                'seniority_level_id_numeric' => 'Nivelul de senioritate specificat nu exista in lista de valori posibile',
                'school_level_id_numeric' => 'Nivelul scolii specificat nu exista in lista de valori posibile',
                'contract_type_id_numeric' => 'Tipul de contract specificat nu exista in lista de valori posibile',
                'description_string' => 'Descrierea trebuie sa fie de tip string',
                'account_mail_required' => 'Adresa de email este obligatorie',
                'account_mail_between' => 'Adresa de email trebuie sa aiba intre 6 si 255 de caractere',
                'account_mail_email' => 'Adresa de email nu este valida',
                'account_mail_unique' => 'Adresa de email este deja folosita',
                'account_new_password_required' => 'Noua parola este obligatorie',
                'account_new_password_between_s' => 'Noua parola trebuie sa aiba intre ',
                'account_new_password_between_e' => ' caractere',
                'account_new_password_confirmed' => 'Parolele nu se potrivesc',
                'account_new_password_confirmation_required' => 'Confirmarea noii parole este obligatorie',
                'account_new_password_confirmation_required_with' => 'Confirmarea noii parole este obligatorie',

                'name_required' => 'Numele este obligatoriu',
                'name_regex' => 'Numele poate contine doar litere, spatiu si caracterul -',
                'email_required' => 'Adresa de email este obligatorie',
                'email_between' => 'Adresa de email trebuie sa aiba intre 6 si 255 de caractere',
                'email_email' => 'Adresa de email nu este valida',
                'email_unique' => 'Adresa de email este deja folosita',
                'identifier_required' => 'CNP-ul este obligatoriu pentru identificare unica',
                'identifier_unique' => 'CNP-ul introdus este deja folosit',
                'identifier_invalid' => 'CNP-ul introdus nu este valid',
            ],
            'message' => [
                'teacher_not_linked' => 'Nu puteti modifica un profesor neasociat',
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a esuat',
                'description_update_successful' => 'Descrierea a fost actualizata cu succes',
                'description_update_failed' => 'Actualizarea descrierii a esuat',
                'login_required' => 'Trebuie sa fiti autentificat',
                'avatar_update_successful' => 'Poza de profil a fost actualizata cu succes',
                'avatar_update_failed' => 'Actualizarea pozei de profil a esuat',
                'email_update_successful' => 'Adresa de email a fost actualizata cu succes',
                'password_update_successful' => 'Parola a fost actualizata cu succes',
                'teachers_import_successful_1' => ' profesori au fost adaugati cu succes si pentru ',
                'teachers_import_successful_2' => ' au aparut erori',
                'teachers_import_failed' => 'Nu a fost specificat un fisier.',
                'teachers_import_sheet_skipped' => 'Sheet-ul urmator nu a fost parcurs: ',
                'teachers_import_failed' => 'Import-ul a esuat.',
                'teachers_import_association_successful' => 'Profesorul a fost asociat cu succes',
                'teachers_import_association_exists' => 'Profesorul este deja asociat',
                'teachers_import_add_successful' => 'Profesorul a fost adaugat cu succes',
                'not_exists' => 'Profesorul specificat nu exista sau nu sunteti asociat cu el',
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
                'learning_plan_id_required' => 'Nu a fost selectat un plan de invatare',
                'learning_plan_id_numeric' => 'Planul de invatare selectat nu este corect',
                'learning_plan_id_exists' => 'Planul de invatare selectat nu exista',
                'course_id_required' => 'Nu a fost selectat un curs',
                'course_id_numeric' => 'Cursul selectat nu este corect',
                'course_id_exists' => 'Cursul selectat nu exista',
                'school_budget_id_required' => 'Nu a fost selectata o scoala',
                'school_budget_id_numeric' => 'Scoala selectata nu este corecta',
                'school_budget_id_exists' => 'Scoala selectata nu exista',
                'school_covered_costs_present' => 'Costurile decontate trebuie sa fie trimise',
                'school_covered_costs_numeric' => 'Costurile decontate trebuie sa fie numerice',
                'school_covered_costs_max' => 'Costurile decontate nu pot sa fie mai mari decat valoarea cursului',
            ],
            'message' => [
                'course_added_successful' => 'Cursul a fost adaugat cu succes',
                'request_added_successful' => 'Cerere de curs creata cu succes',
                'course_deleted_successful' => 'Cursul a fost sters cu succes',
                'participation_updated_successful' => 'Participarea la curs a fost actualizata cu succes',
                'school_course_deleted_not_allowed' => 'Nu puteti sa stergeti un curs adaugat de catre o alta scoala',
                'school_not_valid' => 'Scoala selectata nu exista sau nu sunteti asociat cu ea',
                'course_deleted_not_allowed' => 'Nu puteti sa stergeti un curs marcat ca obligatoriu de catre o scoala'
            ],
        ],

        'appraisal' => [
            'name' => 'Appraisal',
            'description' => 'Allows to view and update an appraisal',
            'validation' => [
            ],
            'message' => [
                'not_allowed' => 'Nu aveti acces la evaluarea ceruta',
                'not_exists' => 'Evaluarea ceruta nu exista',
                'empty_objectives' => 'Obiectivele nu pot sa fie goale',
                'save_successful' => "Schimbarile au fost salvate cu succes",
                'objectives_set_successful' => "Obiectivele au fost stabilite cu succes",
                'objectives_approved_successful' => "Obiectivele au fost aprobate cu succes",
                'skills_set_successful' => "Skill-urile au fost stabilite cu succes",
                'close_successful' => "Evaluarea a fost inchisa cu succes",
                'invalid_grade' => "Notele pot sa fie intre 1 si 10",
                'teacher_not_exists' => 'Profesorul specificat nu exista sau nu sunteti asociat cu el',
                'wrong_percentage' => 'Suma ponderilor trebuie sa fie egala cu 100',
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
                'teachers_financed' => 'Profesori finantati',
                'accredited_courses' => 'Cursuri acreditate',
                'noncredited_courses' => 'Cursuri neacreditate',
                'school_spent_money' => 'Costuri scoala RON',
                'teachers_spent_money' => 'Costuri profesori RON',
                'school_budget' => 'Buget total scoala RON',
                'teachers_budget' => 'Buget total profesori RON',
                'school_paid_courses' => 'Cursuri platite de scoala',
                'teachers_paid_courses' => 'Cursuri platite de profesori',
                'first_semester' => ['0' => 'August', '1' => 'Septembrie', '2' => 'Octombrie', '3' => 'Noiembrie', '4' => 'Decembrie', '5' => 'Ianuarie'],
                'second_semester' => ['0' => 'Februarie', '1' => 'Martie', '2' => 'Aprilie', '3' => 'Mai', '4' => 'Iunie', '5' => 'Iulie'],
                'compare_semester' => ['0' => 'Luna 1', '1' => 'Luna 2', '2' => 'Luna 3', '3' => 'Luna 4', '4' => 'Luna 5', '5' => 'Luna 6'],
                'first_semester_distributed_costs_label' => 'Semestrul 1 ',
                'second_semester_distributed_costs_label' => 'Semestru 2 ',

                'datatable_name' => 'Nume',
                'datatable_identifier' => 'CNP',
                'datatable_birthdate' => 'Data Nastere',
                'datatable_email' => 'Email',
                'datatable_phone' => 'Telefon',
                'datatable_birth_date' => 'Data Nastere',
                'datatable_description' => 'Descriere',
                'datatable_address' => 'Adresa',
                'datatable_seniority' => 'Senioritate',
                'datatable_school_level' => 'Nivel Scolar',
                'datatable_contract_type' => 'Tip Contract',
                'datatable_grade' => 'Grad',
                'datatable_specialization_1' => 'Specializare 1',
                'datatable_specialization_2' => 'Specializare 2',
                'datatable_skill_1' => 'Competenta 1 (C1)',
                'datatable_skill_grade_1' => 'Nota C1',
                'datatable_skill_percentage_1' => 'Procent C1',
                'datatable_skill_2' => 'Competenta 2 (C2)',
                'datatable_skill_grade_2' => 'Nota C2',
                'datatable_skill_percentage_2' => 'Procent C2',
                'datatable_skill_3' => 'Competenta 3 (C3)',
                'datatable_skill_grade_3' => 'Nota C3',
                'datatable_skill_percentage_3' => 'Procent C3',
                'datatable_skill_grades_average' => 'Medie competente',
            ],

            'validation' => [
                'invalid_budget' => 'Bugetul trebuie sa minim 0',
            ],

            'message' => [
                'login_required' => 'Trebuie sa fiti autentificat',
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
