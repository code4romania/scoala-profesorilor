<?php namespace Genuineq\Courses;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerFormWidgets()
    {
        return [
            'Genuineq\Courses\FormWidgets\CategoryTagRelation' => [
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
    }
}
