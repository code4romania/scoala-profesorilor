<?php

return [
    'plugin' => [
        'name' => 'reCAPTCHA',
        'description' => 'Google reCAPTCHA v2 or v3 for October CMS.',
    ],
    'component' => [
        'name' => 'reCAPTCHA',
        'description' => 'Includes Google reCAPTCHA scripts and renders the submit button',
        'type_title' => 'Type of reCAPTCHA',
        'type_description' => '"Visible" and "invisible" types require a reCAPTCHA v2 key pair, and "v3" requires a reCAPTCHA v3 key pair.',
        'load_script_title' => 'Load external JavaScript',
        'load_script_description' => 'If left unchecked, you will need to manually include the reCAPTCHA JavaScript in your HTML. You can then, for example, include the reCAPTCHA v3 script file on every page load.',
        'action_name_title' => 'reCAPTCHA v3 action name',
        'button_text_title' => 'Submit button text',
        'button_text_description' => 'This will be the text on the form\'s submit button.',
        'button_class_title' => 'Submit button class',
        'button_class_description' => 'Will be added to the "class" attribute of the submit button. Separate classes by a space.',
    ],
    'settings' => [
        'label' => 'Google reCAPTCHA Settings',
        'description' => 'Manage reCAPTCHA API keys.',
        'hint1' => 'To use reCAPTCHA, you need to <a href="http://www.google.com/recaptcha/admin" target="_blank">sign up for an API key pair</a> for your site. The key pair consists of a site key and secret key. The secret key needs to be kept safe for security purposes.',
        'hint2' => '<b>Important:</b> Be sure that the key pair version matches the reCAPTCHA version you will be including into the site. You cannot use v3 keys with v2 reCAPTCHA, or vice versa.',
    ],
    'strings' => [
        'error' => 'reCAPTCHA error',
        'site_key' => 'Site key',
        'secret_key' => 'Secret key',
        'language' => 'Language',
    ],
    'errors' => [
        'validation_error' => 'reCAPTCHA error',
        'missing_site_key' => 'Error: Missing reCAPTCHA site key.',
        'invalid_type' => 'Invalid reCAPTCHA type. Check your backend settings.',
    ],
];
