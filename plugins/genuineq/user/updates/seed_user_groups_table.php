<?php namespace Genuineq\User\Updates;

use Genuineq\User\Models\UserGroup;
use October\Rain\Database\Updates\Seeder;
use Genuineq\User\Helpers\PluginConfig;

class SeedUserGroupsTable extends Seeder
{
    public function run()
    {
        foreach (PluginConfig::getUserGroups() as $key => $group) {
            UserGroup::create([
                'name' => $group['name'],
                'code' => $group['code'],
                'description' => $group['description']
            ]);
        }
    }
}
