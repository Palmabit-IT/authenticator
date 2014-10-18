<?php namespace Palmabit\Authentication\Classes;

use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Validators\ReminderValidator;
use Palmabit\Library\Exceptions\MailException;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Library\Exceptions\InvalidException;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use Palmabit\Library\Email\MailerInterface;
use Palmabit\Authentication\Interfaces\AuthenticatorInterface;

/**
 * Class ReminderService
 * Service to send email and error handling
 *
 * @package Auth
 */
class ReminderService {

  /**
   * Class to send email
   *
   * @var MailerInterface
   */
  protected $mailer;
  /**
   * Input validator
   *
   * @var
   */
  protected $validator;
  /**
   * Email body
   *
   * @var string
   */
  protected $body;
  /**
   * Email subject
   */
  protected $subject = "Recupero password";
  /**
   * Femplate mail file
   *
   * @var string
   */
  protected $template = "authentication::auth.mailmessage";
  /**
   * Errors
   *
   * @var \Illuminate\Support\\MessageBag
   */
  protected $errors;
  /**
   * @var \Palamabit\Authentication\Interfaces\AuthenticatorInterface
   */
  protected $auth;

  public function __construct(ReminderValidator $validator = null) {
    $this->auth = \App::make('authenticator');
    $this->mailer = \App::make('palmamailer');
    $this->reminderValidator = $validator ? $validator : new ReminderValidator();
    $this->errors = new MessageBag();
  }

  public function send($to) {
    // gets reset pwd code
    try {
      $token = $this->auth->getToken($to);
    } catch (PalmabitExceptionsInterface $e) {
      $this->errors->add('mail', 'Non esistono utenti associati a questa mail');
      throw new UserNotFoundException;
    }

    $this->preparaBody($token, $to);

    // send email with change password link
    $success = $this->mailer->sendTo($to, $this->body, $this->subject, $this->template);

    if (!$success) {
      $this->errors->add('mail', 'C\'è stato un\'errore nell\'invio della mail');
      throw new MailException;
    }
  }

  public function reset($email, $token, $password) {
    try {
      $user = $this->auth->getUser($email);
    } catch (PalmabitExceptionsInterface $e) {
      $this->errors->add('user', 'Non esistono utenti associati a questa mail.');
      throw new UserNotFoundException;
    }

    // Check if the reset password code is valid
    if ($user->checkResetPasswordCode($token)) {
      // Attempt to reset the user password
      if (!$user->attemptResetPassword($token, $password)) {
        $this->errors->add('user', 'Non è stato possibile modificare la password.');
        throw new InvalidException();
      }
    } else {
      $this->errors->add('user', 'Il codice di conferma non è valido.');
      throw new InvalidException();
    }
  }

  public function getErrors() {
    return $this->errors;
  }

  protected function preparaBody($token, $to) {
    $this->body =
            link_to_action("Palmabit\\Authentication\\Controllers\\AuthController@getChangePassword", "Clicca qui per cambiare password.", ["email" => $to,
                                                                                                                                            "token" => $token]);
  }
}