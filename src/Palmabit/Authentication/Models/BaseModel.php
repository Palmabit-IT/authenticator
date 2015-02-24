<?php  namespace Palmabit\Authentication\Models;

/**
 * Class BaseModel
 */
use Illuminate\Database\Eloquent\Model;
use Palmabit\Library\Traits\OverrideConnectionTrait;

class BaseModel extends Model
{
    use OverrideConnectionTrait;
} 