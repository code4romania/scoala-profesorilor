<?php return [
    'plugin' => [
        'name' => 'Courses',
        'description' => 'Contains all the models, views and controllers related to the courses',
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
];
