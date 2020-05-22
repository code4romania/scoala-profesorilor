<?php namespace Genuineq\Tms;

use Event;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Genuineq\User\Models\User;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Classes\SemesterCloser;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Genuineq\Tms\Components\SchoolProfile' => 'schoolProfile',
            'Genuineq\Tms\Components\TeacherProfile' => 'teacherProfile',
            'Genuineq\Tms\Components\SchoolTeacherProfile' => 'schoolTeacherProfile',
            'Genuineq\Tms\Components\CourseSearch' => 'courseSearch',
            'Genuineq\Tms\Components\LearningPlan' => 'learningPlan',
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Genuineq\Tms\FormWidgets\CategoryTagRelation' => [
                'label' => 'Category Tag Relation field',
                'code' => 'categorytagrelation'
            ]
        ];
    }

    public function registerSchedule($schedule)
    {
        /** Task for end of first semester */
        $schedule->call(function () {
            SemesterCloser::closeFirstSemester();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of january'));
        });

        /** Task for end of second semester */
        $schedule->call(function () {
            SemesterCloser::closeSecondSemester();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of june'));
        });
    }

    public function boot()
    {

        /** Extend the "Genuineq\User\Models\User" model. */
        User::extend(function($model) {
            /** Link "School" model to user model */
            $model->hasOne['schoolProfile'] = ['Genuineq\Tms\Models\School'];
            /** Link "Teacher" model to user model */
            $model->hasOne['teacherProfile'] = ['Genuineq\Tms\Models\Teacher'];

            /** Add a "getProfileAttribute" function to the user model */
            $model->addDynamicMethod('getProfileAttribute', function() use ($model) {
                /** Return the valid profile */
                return ($model->schoolProfile) ? ($model->schoolProfile) : ($model->teacherProfile);
            });
        });

        /** Define listener of the "genuineq.user.created" event */
        Event::listen('genuineq.user.created', function ($user, $data) {
            /** Create user profile based on user type. */
            if ('school' == $user->type) {
                $profile = new School(['user_id' => $user->id]);
                $profile->save();
            } else {
                $profile = new Teacher(['name' => $user->name, 'user_id' => $user->id]);
                $profile->save();
            }
        });
    }
}
