<?php  namespace Palmabit\Authentication\Models;

/**
 * Class Group
 */
use Cartalyst\Sentry\Groups\Eloquent\Group as SentryGroup;
use Palmabit\Library\Traits\OverrideConnectionTrait;

class Group extends SentryGroup
{
    use OverrideConnectionTrait;

    protected $guarded = ["id"];

    protected $fillable = ["name", "permissions", "blocked"];
}