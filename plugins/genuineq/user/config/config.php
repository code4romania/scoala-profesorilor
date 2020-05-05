<?php return [
    /** The minimum length of characters required for user passwords. */
    'minPasswordLength' => 8,

    /** The maximum length of user passwords. */
    'maxPasswordLength' => 255,

    /** The available user groups. */
    'userGroups' => [
        'school' => [
            'name' => 'School',
            'code' => 'school',
            'description' => 'Group that contains all users of a school.',
        ],
        'teacher' => [
            'name' => 'Teacher',
            'code' => 'teacher',
            'description' => 'Group that contains all users of a teacher.',
        ],
    ],

    /** The available user types. */
    'userTypes' => [
        'school',
        'teacher'
    ],

    /** The available user type options displayed in the admin view. */
    'userTypeOptions' => [
        'school' => 'School',
        'teacher' => 'Teacher'
    ],

    /** Login redirects based on user types. */
    'loginRedirects' => [
        'school' => '/',
        'teacher' => '/'
    ],

    /** Profile page for each user type. */
    'profilePages' => [
        'school' => 'tms-profil-scoala',
        'teacher' => 'tms-profil-profesor',
    ],
];
