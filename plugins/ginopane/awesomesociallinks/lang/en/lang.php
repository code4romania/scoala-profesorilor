<?php

return [
    'plugin' => [
        'name' => 'Awesome Social Links',
        'description' => 'A list of social icons (or any other branded icons) associated with your website'
    ],
    'settings' => [
        'name' => 'Awesome Social Links',
        'description' => 'Add, remove item, rearrange the list of icons',

        'list_item' => 'List Item',
        'list_item_prompt' => 'Add a New List Item',

        'list_item_icon' => 'Icon',
        'list_item_icon_placeholder' => 'Please select an icon',

        'list_item_name' => 'Name',
        'list_item_name_placeholder' => 'List item name',

        'list_item_url' => 'Link',
        'list_item_url_placeholder' => 'List item URL'
    ],
    'components' => [
        'link_list' => [
            'name' => 'Icon List',
            'description' => 'A list of available icons',
            'link_settings_group' => 'Link Settings',
            'link_target_title' => 'Link Target',
            'link_target_description' => 'The default target attribute for your links'
        ]
    ],
    'validation' => [
        'name_required' => 'Please make sure to name every list item',
        'url_required' => 'Please make sure to set a URL for every list item',
        'url_must_be_valid' => 'Link value must be a valid URL'
    ]
];
