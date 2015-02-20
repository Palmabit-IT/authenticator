<?php

namespace Palmabit\Authentication\Interfaces;


interface GroupsFilterInterface
{

    public function getAll();

    public function getEditableGroups();

    public function getAssignableGroups($userGroup,$listGroups);

} 