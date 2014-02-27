<?php namespace Palmabit\Authentication\Exceptions;
/**
 * Class ProfileNotFoundException
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */

use Exception;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;

class ProfileNotFoundException extends Exception implements PalmabitExceptionsInterface {}