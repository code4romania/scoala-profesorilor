<?php namespace Genuineq\Tms;

use System\Classes\PluginBase;
use Genuineq\User\Models\User;
use Genuineq\Tms\Models\School;
use Genuineq\Tms\Models\Teacher;

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

    public function registerSettings()
    {
    }

    public function boot()
    {
        \Event::listen('offline.sitesearch.query', function ($query) {

            /** The controller is used to generate page URLs. */
            $controller = \Cms\Classes\Controller::getController() ?? new \Cms\Classes\Controller();

            /** Search the courses. */
            $items = Models\Course
                ::where('name', 'like', "%${query}%")
                ->orWhere('description', 'like', "%${query}%")
                ->orWhere('address', 'like', "%${query}%")
                ->get();

            /** Now build a results array. */
            $results = $items->map(function ($item) use ($query) {

                /** If the query is found in the name, set a relevance of 2. */
                $relevance = mb_stripos($item->name, $query) !== false ? 2 : 1;

                /** Optional: Add an age penalty to older results. This makes sure that newer results are listed first. */
                // if ($relevance > 1 && $item->created_at) {
                //    $ageInDays = $item->created_at->diffInDays(\Illuminate\Support\Carbon::now());
                //    $relevance -= \OFFLINE\SiteSearch\Classes\Providers\ResultsProvider::agePenaltyForDays($ageInDays);
                // }

                return [
                    'title'     => $item->name,
                    'text'      => $item->description,
                    // 'url'       => $controller->pageUrl('/', ['slug' => $item->slug]),
                    // 'thumb'     => optional($item->images)->first(), // Instance of System\Models\File
                    'relevance' => $relevance,
                    // 'meta' => 'data',       // optional, any other information you want
                                            // to associate with this result
                    'model' => $item,       // optional, pass along the original model
                ];
            });

            return [
                'provider' => 'Course', // The badge to display for this result
                'results'  => $results,
            ];
        });


        /** Extend the "Genuineq\User\Models\User" model. */
        User::extend(function($model) {
            /** Link "School" model to user model */
            $model->hasOne['schoolProfile'] = ['Genuineq\Tms\Models\School'];
            /** Link "Teacher" model to user model */
            $model->hasOne['teacherProfile'] = ['Genuineq\Tms\Models\Teacher'];

            /** Add a "getProfile" function to the user model */
            $model->addDynamicMethod('getProfile', function() use ($model) {
                /** Return the valid profile */
                return ($model->schoolProfile) ? ($model->schoolProfile) : ($model->teacherProfile);
            });
        });

        /** Define listener of the "genuineq.user.register" event */
        \Event::listen('genuineq.user.register', function ($user, $data) {
            /** Create user profile based on user type. */
            if ('school' == $user->type) {
                $profile = new School(['user_id' => $user->id]);
                $profile->save();

                /** Make the connection */
                // $user->schoolProfile = $profile;
                // $user->save();
            } else {
                $profile = new Teacher(['name' => $user->name, 'user_id' => $user->id]);
                $profile->save();

                /** Make the connection */
                // $user->teacherProfile = $profile;
                // $user->save();
            }
        });
    }
}
