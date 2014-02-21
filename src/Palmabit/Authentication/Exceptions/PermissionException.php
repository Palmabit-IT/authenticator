<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class PermissionException
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class PermissionException extends Exception implements PalmabitExceptionsInterface {}