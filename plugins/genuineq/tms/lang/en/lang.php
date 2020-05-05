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
            'diacritic' => 'Diacritics',
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
            'status' => 'Status',
            'categories' => 'Categories',
            'skills' => 'Skills',
            'supplier' => 'Supplier',
        ],

        'comment' => [
            'accredited' => 'Specifies if a course is accredited or not.',
        ],

        'backend-menu' => 'Courses',

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
    ],

    'supplier' => [
        'label' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'email' => 'Email',
            'phone' => 'Phone',
            'description' => 'Description',
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
            'avatar' => 'Avatar',
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
        ],

        'backend-menu' => 'Teachers',
    ],

    'inspectotate' => [
        'label' => [
            'name' => 'Name',
            'diacritic' => 'Diacritics',
            'description' => 'Description',
        ],

        'backend-menu' => 'Inspectotates',
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
        ],

        'backend-menu' => 'Schools',
    ],

    'learning-plan' => [
        'label' => [
            'year' => 'Year',
            'semester' => 'Semester',
            'courses' => 'Learning Plan Courses',
            'teacher' => 'Teacher',
        ],

        'backend-menu' => 'Learning Plans',
    ],

    'learning-plans-course' => [
        'label' => [
            'id' => '#',
            'learning-plan' => 'Learning Plan',
            'course' => 'Course',
            'school' => 'School',
            'covered-costs' => 'School Covered Costs',
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
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a esuat',
                'description_update_successful' => 'Descrierea a fost actualizata cu succes',
                'description_update_failed' => 'Actualizarea descrierii a esuat',
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
                'seniority_level_id_numeric' => 'Nuvelul de senioritate specificat nu exista in lista de valori posibile',
                'school_level_id_numeric' => 'Nivelul scolii specificat nu exista in lista de valori posibile',
                'contract_type_id_numeric' => 'Tipul de contract specificat nu exista in lista de valori posibile',
                'description_string' => 'Descrierea trebuie sa fie de tip string',
            ],
            'message' => [
                'profile_update_successful' => 'Profilul a fost actualizat cu succes',
                'profile_update_failed' => 'Actualizarea profilului a esuat',
                'description_update_successful' => 'Descrierea a fost actualizata cu succes',
                'description_update_failed' => 'Actualizarea descrierii a esuat',
                'login_required' => 'Trebuie sa fiti autentificat',
            ],
        ],
    ],
];
