<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class GroupNotFoundException
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class GroupNotFoundException extends Exception implements PalmabitExceptionsInterface {}