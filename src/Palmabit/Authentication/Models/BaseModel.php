<?php  namespace Palmabit\Authentication\Models; 
/**
 * Class BaseModel
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
use Illuminate\Database\Eloquent\Model;
use Palmabit\Library\Traits\OverrideConnectionTrait;

class BaseModel extends Model
{
    use OverrideConnectionTrait;
} 