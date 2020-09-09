<?php namespace AjayLulia\OCookie\Models;

use Model;
use DB;
/**
 * Model
 */
class Config extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'ajaylulia_ocookie_configuration';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    // public $attachMany = [
    //     'pointer_images' => ['System\Models\File', 'order' => 'sort_order'],
    //     'content_images'  => ['System\Models\File']
    // ];


    
}
