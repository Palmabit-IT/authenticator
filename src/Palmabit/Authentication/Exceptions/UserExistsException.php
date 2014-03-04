<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class UserExistsException
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class UserExistsException extends Exception implements PalmabitExceptionsInterface {}