<?php namespace Genuineq\Tms\Models;

use Model;
use Genuineq\Tms\Models\Course;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\SoftDelete;
    use \Jacob\Logbook\Traits\LogChanges;

    protected $dates = ['deleted_at'];

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'slug',
        'color',
        'icon',
        'description'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'genuineq_tms_categories';

    /**
     * Here you can override the model name that is displayed in the log files.
     * The name is going to be translated when possible.
     */
    public $logBookModelName = 'Category';

    /**
     * Delete log book items after model is deleted
     *
     * If true -&gt; log items are deleted when the model is deleted
     * If false -&gt; a new log item will be created with status deleted.
     *
     * @var bool
     */
    protected $deleteLogbookAfterDelete = true;

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    /**
     * Categories relation
     */
    public $belongsToMany = [
        'courses' => [
            'Genuineq\Tms\Models\Course',
            'table' => 'genuineq_tms_courses_categories',
            'order' => 'name',
        ],
    ];

    /***********************************************
     ******************** Events *******************
     ***********************************************/

    /**
     * Function that executed before the creation of an event;
     */
    public function beforeCreate()
    {
        $this->slug = str_slug($this->name, '-');
    }

    /**
     * Function used for searching, filtering, sorting and paginating the school courses.
     *
     * @param options An array of options to use.
     */
    public function filterCourses($options = []){
        /** Add the category ID */
        $options['category'] = $this->id;

        return Course::filterCourses($options);
    }
}
