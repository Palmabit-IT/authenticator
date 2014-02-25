<?php  namespace Palmabit\Authentication\Models; 
/**
 * Class BaseModel
 *
 * @author jacopo beschi jacopo@jacopobeschi.com
 */
use Illuminate\Database\Eloquent\Model;
use Palmabit\Authentication\Traits\OverrideConnectionTrait;

class BaseModel extends Model
{
    use OverrideConnectionTrait;
} 