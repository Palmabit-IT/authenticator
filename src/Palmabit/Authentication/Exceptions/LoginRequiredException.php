<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class UserNotFoundException
 *
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class LoginRequiredException extends Exception implements PalmabitExceptionsInterface {}