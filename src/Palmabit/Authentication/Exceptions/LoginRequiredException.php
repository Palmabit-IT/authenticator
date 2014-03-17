<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class UserNotFoundException
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class LoginRequiredException extends Exception implements PalmabitExceptionsInterface {}