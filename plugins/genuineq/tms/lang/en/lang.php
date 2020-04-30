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
];
