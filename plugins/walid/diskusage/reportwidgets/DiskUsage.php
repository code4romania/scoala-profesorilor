<?php namespace Walid\DiskUsage\ReportWidgets;

use Log;
use Backend;
use Backend\Classes\ReportWidgetBase;
use Walid\DiskUsage\Models\Settings;

class DiskUsage extends ReportWidgetBase
{
    public $disks = null;

    public $error = null;

    public function defineProperties()
    {
        return [
            'disk' => [
                'title'             => 'Disk',
                'default'           => null,
                'type'              => 'dropdown'
            ]
        ];
    }

    public function getDiskOptions()
    {
        return array_reduce($this->getDisks(), function($carry, $disk) {
            $carry[$disk['name']] = $disk['name'];
            return $carry;
        }, []);
    }

    public function getDisks()
    {
        if (!$this->disks) {
            $disks = Settings::get('disks') ?: [];

            $this->disks = array_reduce($disks, function($carry, $disk) {
                $carry[] = $disk + [
                    'free_space' => $this->formatBytes(disk_free_space($disk['path'])),
                    'total_space' => $this->formatBytes(disk_total_space($disk['path'])),
                    'used_space' => $this->formatBytes(
                        disk_total_space($disk['path']) - disk_free_space($disk['path'])
                        )
                    ];
                    return $carry;
                }, []);
            }

            return $this->disks;
        }

        public function getDisk()
        {
            $disk = array_values(array_filter($this->getDisks(), function($disk) {
                if ($this->property('disk')) {
                    return $disk['name'] === $this->property('disk');
                }
                return true;
            }));

            if (!$disk) {
                $this->error = 'Unable to find disk, please <a href="'.$this->getPluginSettingsUrl().'">add some disks</a> then select a disk from widget options';
                return null;
            }


            return $disk[0];
        }

        public function render()
        {
            return $this->makePartial('widget', ['disk' => $this->getDisk(), 'error' => $this->error]);
        }

        public function formatBytes($bytes)
        {
            $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );

            $base = 1024;

            $class = min((int)log($bytes , $base) , count($si_prefix) - 1);

            $prefix = $si_prefix[$class];

            $number = sprintf('%1.2f' , $bytes / pow($base,$class));

            $formatted = "$number $prefix";
            return compact('prefix', 'number', 'formatted');
        }

        public function getPluginSettingsUrl()
        {
            return Backend::url('system/settings/update/walid/diskusage/settings');
        }
    }
