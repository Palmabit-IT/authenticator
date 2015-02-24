<?php  namespace Palmabit\Authentication\Interfaces;

/**
 * Interface AuthenticationRoutesInterface
 */
interface AuthenticationRoutesInterface
{
    /**
     * Obtain the permissions from a given url
     *
     * @param $route
     * @return mixed
     */
    public function getPermFromRoute($route);

    /**
     * Obtain the permissions from the current url
     *
     * @return mixed
     */
    public function getPermFromCurrentRoute();
}