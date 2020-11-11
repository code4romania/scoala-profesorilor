<?php

namespace OnePilot\LogsWidget\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Carbon\Carbon;
use System\Models\EventLog;

class ErrorsOverview extends ReportWidgetBase
{
    /** @var array intervals in minutes */
    const INTERVALS = [
        '24 hours' => 1 * 24 * 60,
        '7 days'   => 7 * 24 * 60,
        '30 days'  => 30 * 24 * 60,
    ];

    /** @var string[] */
    const ERRORS_TO_COUNT = [
        'emergency',
        'alert',
        'critical',
        'error',
    ];

    /**
     * @return mixed|string
     * @throws \SystemException
     */
    public function render()
    {
        $this->addCss('css/widget.css');

        return $this->makePartial('widget', ['errors' => $this->overview()]);
    }

    /**
     * @return array
     */
    public function overview()
    {
        return collect(self::INTERVALS)->mapWithKeys(function ($interval, $key) {
            return [
                $key => [
                    'last'     => $this->errorsCounter(
                        now()->subMinutes($interval), now()
                    ),
                    'previous' => $this->errorsCounter(
                        now()->subMinutes($interval * 2), now()->subMinutes($interval)
                    ),
                ],
            ];
        });
    }

    /**
     * @param Carbon $from
     * @param Carbon $to
     *
     * @return int
     */
    private function errorsCounter(Carbon $from, Carbon $to)
    {
        return EventLog::select('level')
            ->selectRaw('count(*) as total')
            ->where('created_at', '>', $from)
            ->where('created_at', '<', $to)
            ->whereIn('level', self::ERRORS_TO_COUNT)
            ->groupBy('level')
            ->value('total');
    }
}
