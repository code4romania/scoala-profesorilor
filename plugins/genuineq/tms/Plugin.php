<?php namespace Genuineq\Tms;

use Event;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Genuineq\User\Models\User;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Classes\SemesterCloser;
use RainLab\Notify\Classes\Notifier;

class Plugin extends PluginBase
{
    /**
     * @var boolean Determine if this plugin should have elevated privileges.
     */
    public $elevated = true;

    public function register()
    {
        /** Compatability with Rainlab.Notify */
        $this->bindNotificationEvents();
    }

    public function registerComponents()
    {
        return [
            'Genuineq\Tms\Components\SchoolProfile' => 'schoolProfileComponent',
            'Genuineq\Tms\Components\TeacherProfile' => 'teacherProfileComponent',
            'Genuineq\Tms\Components\SchoolTeacherProfile' => 'schoolTeacherProfileComponent',
            'Genuineq\Tms\Components\CourseSearch' => 'courseSearchComponent',
            'Genuineq\Tms\Components\LearningPlan' => 'learningPlanComponent',
            'Genuineq\Tms\Components\Appraisal' => 'appraisalComponent',
            'Genuineq\Tms\Components\Budget' => 'budgetComponent',
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

    public function registerMailTemplates()
    {
        return [
            'genuineq.tms::mail.teacher-course-request',
            'genuineq.tms::mail.teacher-course-approve',
            'genuineq.tms::mail.teacher-course-reject',
            'genuineq.tms::mail.school-course-request',
            'genuineq.tms::mail.school-course-approve',
            'genuineq.tms::mail.school-course-reject',
        ];
    }

    public function registerNotificationRules()
    {
        return [
            'events' => [
                \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
                \Genuineq\Tms\NotifyRules\TeacherCourseApproveEvent::class,
                \Genuineq\Tms\NotifyRules\TeacherCourseRejectEvent::class,

                \Genuineq\Tms\NotifyRules\SchoolCourseRequestEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolCourseApproveEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolCourseRejectEvent::class,
            ],
            'actions' => [],
            'conditions' => [
                \Genuineq\Tms\NotifyRules\TeacherAttributeCondition::class,
                \Genuineq\Tms\NotifyRules\SchoolAttributeCondition::class,
                \Genuineq\Tms\NotifyRules\LearningPlanCourseAttributeCondition::class,
            ],
            'groups' => [
                'tms' => [
                    'label' => 'TMS',
                    'icon' => 'icon-book'
                ],
            ],
        ];
    }

    protected function bindNotificationEvents()
    {
        if (!class_exists(Notifier::class)) {
            return;
        }

        Notifier::bindEvents([
            'genuineq.tms.teacher.course.request' => \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
            'genuineq.tms.teacher.course.approve' => \Genuineq\Tms\NotifyRules\TeacherCourseApproveEvent::class,
            'genuineq.tms.teacher.course.reject' => \Genuineq\Tms\NotifyRules\TeacherCourseRejectEvent::class,

            'genuineq.tms.school.course.request' => \Genuineq\Tms\NotifyRules\SchoolCourseRequestEvent::class,
            'genuineq.tms.school.course.approve' => \Genuineq\Tms\NotifyRules\SchoolCourseApproveEvent::class,
            'genuineq.tms.school.course.reject' => \Genuineq\Tms\NotifyRules\SchoolCourseRejectEvent::class,

            // 'genuineq.tms.teacher.appraisal.objectives.set' => \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,

            // 'genuineq.tms.school.appraisal.objectives.approve' => \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
            // 'genuineq.tms.school.appraisal.objectives.reject' => \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
            // 'genuineq.tms.school.appraisal.evaluation.done' => \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
        ]);
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
        Event::listen('genuineq.user.created', function ($user) {
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
