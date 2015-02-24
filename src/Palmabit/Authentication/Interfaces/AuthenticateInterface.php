<?php namespace Palmabit\Authentication\Interfaces;

interface AuthenticateInterface
{
    /**
     * Effettua l'autenticazione di un utente
     *
     * @param $credentials
     * @param $remember
     * @return mixed
     */
    public function authenticate($credentials, $remember);

    /**
     * Login a predefined user
     *
     * @param $id
     * @param $remember
     * @return mixed
     */
    public function loginById($id, $remember);

    /**
     * Logout
     *
     * @return mixed
     */
    public function logout();

    /**
     * Return auth errors or others
     *
     * @return mixed
     */
    public function getErrors();

    /**
     * Ritorna l'utente data la sua mail
     *
     * @param $email
     * @return mixed
     * @throws \Palmabit\Authentication\Exceptions\UserNotFoundException
     * @return mixed
     */
    public function getUser($email);

    /**
     * Obtain the currently logged in user is exists
     *
     * @return mixed
     */
    public function getLoggedUser();

    /**
     * Ritorna il token associato alla mail
     *
     * @param $email
     * @return String
     */
    public function getToken($email);

    /**
     * Check if the user is logged in and is active
     *
     * @return boolean
     */
    public function check();

    /**
     * Obtain the current user groups
     *
     * @return mixed
     */
    public function getGroups();

    /**
     * Check if the current user has the given group
     *
     * @param $name
     * @return mixed
     */
    public function hasGroup($name);

    /**
     * Finds a user given an id
     *
     * @param $id
     * @return mixed
     */
    public function findById($id);
}