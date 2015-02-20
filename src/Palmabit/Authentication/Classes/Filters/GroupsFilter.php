<?php namespace Palmabit\Authentication\Classes\Filters;


use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Palmabit\Authentication\Interfaces\GroupsFilterInterface;
use Palmabit\Authentication\Models\Group;

class GroupsFilter implements GroupsFilterInterface
{


    public function getAll()
    {
        return Group::all();
    }

    public function getEditableGroups()
    {
        $groups = new Group();
        return $groups->where('permissions', 'LIKE', '%editable%')->get();
    }


    public function getAssignableGroups($user,$listGroups)
    {
        $userGroups = $user->getGroups();
        $groups = Config::get('authentication::no_access_group');
        foreach ($userGroups as $userGroup) {
            $listGroupsToExclude = $groups[$userGroup->name];
            $listAssignaleGroups = array_diff($listGroups,$listGroupsToExclude);
            return $listAssignaleGroups;
        }
    }
}