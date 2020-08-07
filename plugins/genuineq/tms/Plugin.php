<?php namespace Genuineq\Tms;

use Event;
use Carbon\Carbon;
use System\Classes\PluginBase;
use Genuineq\User\Models\User;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Classes\PeriodicTasks;
use RainLab\Notify\Classes\Notifier;
use Mail;

class Plugin extends PluginBase
{
    /**
     * @var array   Require the dependency plugins
     */
    public $require = ['RainLab.Builder', 'RainLab.Translate', 'Genuineq.User', 'Genuineq.Https', 'RainLab.Notify', 'Rahman.Faker'];

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
            'Genuineq\Tms\Components\SchoolDashboard' => 'schoolDashboardComponent'
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Genuineq\Tms\FormWidgets\CategoryTagRelation' => [
                'label' => 'Category Tag Relation field',
                'code' => 'categorytagrelation'
            ],
            'Genuineq\Tms\FormWidgets\SkillTagRelation' => [
                'label' => 'Skill Tag Relation field',
                'code' => 'skilltagrelation'
            ]
        ];
    }

    public function registerSchedule($schedule)
    {

        /** Task for start of the last month of the first semester */
        $schedule->call(function () {
            PeriodicTasks::openFirstSemesterAppraisals();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of december'));
        });

        /** Task for end of first semester */
        $schedule->call(function () {
            PeriodicTasks::closeFirstSemester();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of january'));
        });



        /** Task for start of the last month of the second semester */
        $schedule->call(function () {
            PeriodicTasks::openSecondSemesterAppraisals();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of june'));
        });

        /** Task for end of second semester */
        $schedule->call(function () {
            PeriodicTasks::closeSecondSemester();
        })->dailyAt('00:00')->when(function () {
            return Carbon::today() == (new Carbon('last day of july'));
        });

        // $schedule->call(function () {
        //     Mail::rawTo('cosmin.bosutar@genuineq.com', 'Hello friend');
        // })->everyMinute();
    }

    public function registerMailTemplates()
    {
        return [
            'genuineq.tms::mail.teacher-course-request',
            'genuineq.tms::mail.teacher-course-approve',
            'genuineq.tms::mail.teacher-course-reject',
            'genuineq.tms::mail.teacher-objectives-set',

            'genuineq.tms::mail.school-course-request',
            'genuineq.tms::mail.school-course-approve',
            'genuineq.tms::mail.school-course-reject',
            'genuineq.tms::mail.school-objectives-approve',
            'genuineq.tms::mail.school-skills-set',
            'genuineq.tms::mail.school-evaluation-close'
        ];
    }

    public function registerNotificationRules()
    {
        return [
            'events' => [
                \Genuineq\Tms\NotifyRules\TeacherCourseRequestEvent::class,
                \Genuineq\Tms\NotifyRules\TeacherCourseApproveEvent::class,
                \Genuineq\Tms\NotifyRules\TeacherCourseRejectEvent::class,
                \Genuineq\Tms\NotifyRules\TeacherObjectivesSetEvent::class,

                \Genuineq\Tms\NotifyRules\SchoolCourseRequestEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolCourseApproveEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolCourseRejectEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolObjectivesApproveEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolSkillsSetEvent::class,
                \Genuineq\Tms\NotifyRules\SchoolEvaluationClosedEvent::class,
            ],
            'actions' => [],
            'conditions' => [
                \Genuineq\Tms\NotifyRules\TeacherAttributeCondition::class,
                \Genuineq\Tms\NotifyRules\SchoolAttributeCondition::class,
                \Genuineq\Tms\NotifyRules\LearningPlanCourseAttributeCondition::class,
                \Genuineq\Tms\NotifyRules\AppraisalAttributeCondition::class,
            ],
            'groups' => [
                'tms' => [
                    'label' => 'TMS',
                    'icon' => 'icon-book'
                ],
            ],
        ];
    }

    public function registerReportWidgets()
    {
        return [
            'Genuineq\Tms\ReportWidgets\TotalTeachers' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.totalteachers.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\TotalSchools' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.totalschools.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\TotalCourses' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.totalcourses.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\TotalSkills' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.totalskills.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\TotalSuppliers' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.totalsuppliers.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\LearningPlanCompletion' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.learningplancompletion.label',
                'context' => 'dashboard',
            ],
            'Genuineq\Tms\ReportWidgets\CoursesTop' => [
                'label'   => 'genuineq.tms::lang.reportwidgets.coursestop.label',
                'context' => 'dashboard',
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
            'genuineq.tms.teacher.appraisal.objectives.set' => \Genuineq\Tms\NotifyRules\TeacherObjectivesSetEvent::class,

            'genuineq.tms.school.course.request' => \Genuineq\Tms\NotifyRules\SchoolCourseRequestEvent::class,
            'genuineq.tms.school.course.approve' => \Genuineq\Tms\NotifyRules\SchoolCourseApproveEvent::class,
            'genuineq.tms.school.course.reject' => \Genuineq\Tms\NotifyRules\SchoolCourseRejectEvent::class,
            'genuineq.tms.school.appraisal.objectives.approve' => \Genuineq\Tms\NotifyRules\SchoolObjectivesApproveEvent::class,
            'genuineq.tms.school.appraisal.skills.set' => \Genuineq\Tms\NotifyRules\SchoolSkillsSetEvent::class,
            'genuineq.tms.school.appraisal.evaluation.closed' => \Genuineq\Tms\NotifyRules\SchoolEvaluationClosedEvent::class,
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
                $profile = new School(['contact_name' => $user->name, 'contact_email' => $user->email, 'user_id' => $user->id]);
                $profile->save();
            } else {
                $profile = new Teacher(['name' => $user->name, 'user_id' => $user->id]);
                $profile->save();
            }
        });

        /** register middleware for trusted proxies */
        $this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware('Genuineq\Tms\Middlewares\TrustProxies');
    }
}
