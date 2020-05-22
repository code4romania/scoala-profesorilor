<?php

namespace GinoPane\AwesomeIconsList\Updates;

use DB;
use Schema;
use October\Rain\Database\Updates\Migration;

/**
 * Class UpdateSettingsTo581
 *
 * @package GinoPane\AwesomeIconsList\Updates
 */
class UpdateSettingsTo582 extends UpdateSettingsAbstract
{
    protected static $PREVIOUS_FONTAWESOME_LINK = 'https://use.fontawesome.com/releases/v5.8.1/css/all.css';
    protected static $PREVIOUS_FONTAWESOME_LINK_ATTRIBUTES = [
        [
            'attribute' => 'integrity',
            'value'     => 'sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf'
        ],
        [
            'attribute' => 'crossorigin',
            'value'     => 'anonymous'
        ]
    ];

    protected static $NEW_FONTAWESOME_LINK = 'https://use.fontawesome.com/releases/v5.8.2/css/all.css';
    protected static $NEW_FONTAWESOME_LINK_ATTRIBUTES = [
        [
            'attribute' => 'integrity',
            'value'     => 'sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay'
        ],
        [
            'attribute' => 'crossorigin',
            'value'     => 'anonymous'
        ]
    ];
}
