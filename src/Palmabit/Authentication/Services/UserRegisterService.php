<?php  namespace Palmabit\Authentication\Services;
use Illuminate\Support\Facades\App;
use Config, Redirect, DB;
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\UserExistsException;
use Palmabit\Authentication\Exceptions\UserNotFoundException;
use Palmabit\Authentication\Validators\UserSignupValidator;
use Palmabit\Library\Exceptions\NotFoundException;
use Palmabit\Library\Exceptions\PalmabitExceptionsInterface;
use Palmabit\Library\Exceptions\ValidationException;

/**
 * Class UserRegisterService
 *
 * @author jacopo beschi j.beschi@palmabit.com
 */
class UserRegisterService 
{
    /**
     * @var \Palmabit\Authentication\Repository\Interfaces\UserRepositoryInterface
     */
    protected $u_r;
    /**
     * @var \Palmabit\Authentication\Repository\Interfaces\UserProfileRepositoryInterface
     */
    protected $p_r;
    /**
     * @var \Palmabit\Authentication\Validators\UserSignupValidator
     */
    protected $v;
    /**
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    public function __construct(UserSignupValidator $v = null)
    {
        $this->u_r = App::make('user_repository');
        $this->v = $v ? $v : new UserSignupValidator;
    }

    public function register(array $input)
    {
        // for default user is not active and new user at registration
        $input["activated"] = false;
        $input["new_user"] = true;

        $this->validateInput($input);

        $user = $this->saveDbData($input);

        $mailer = App::make('palmamailer');
        $this->sendMailToClient($mailer, $user, $input);
        $this->sendMailToAdmins($mailer, $user, $input);
    }

    /**
     * @param $mailer
     * @param $user
     */
    protected function sendMailToClient($mailer, $user, array $input)
    {
        if( $user->activated) return;

        $view = $user->new_user ? "authentication::mail.registration-client-new" : "authentication::mail.registration-client-exists";

        // send email to client
        $mailer->sendTo( $input['email'], [ "email" => $input["email"], "password" => $input["password"] ], "Richiesta registrazione su: " . \Config::get('authentication::app_name'), $view);
    }

    /**
     * @param $mailer
     */
    protected function sendMailToAdmins($mailer, $user, array $input)
    {
        // send email to admins
        $mail_helper = App::make('authentication_helper');
        $mails       = $mail_helper->getNotificationRegistrationUsersEmail();
        if (!empty($mails)) foreach ($mails as $mail)
        {
            $mailer->sendTo($mail, [ "email" => $user->email ], "Richiesta di registrazione utente", "authentication::mail.registration-request-admin.blade");
        }
    }

    /**
     * Send activation email to the client if it's getting activated
     * @param $user
     */
    public function sendActivationEmailToClient($user, array $input = null)
    {
        if( ! $user->activated) return;

        $mailer = App::make('palmamailer');
        // if i activate a deactivated user
        $mailer->sendTo($user->email, [ "email" => $user->email ], "Sei stato attivato su ".Config::get('authentication::app_name'), "authentication::mail.registration-activated-client");
    }

    /**
     * @param array $input
     * @return mixed $user
     */
    protected function saveDbData(array $input)
    {
        if(App::environment() != 'testing')
        {
            // temporary disable reference integrity check
            DB::connection('authentication')->getPdo()->exec('SET FOREIGN_KEY_CHECKS=0;');
            DB::connection('authentication')->getPdo()->beginTransaction();
        }

        try
        {
            // try to update the user
            $user = $this->u_r->findByLogin($input["email"]);
            $user = $this->u_r->update($user->id, ["password" => $input["password"]]);
        }
        catch(UserNotFoundException $e)
        {
            $user = false;
        }

        try
        {
            // fallback into creating a new user
            if(! $user) $user = $this->u_r->create($input);
        }
        catch(UserExistsException $e)
        {
            if(App::environment() != 'testing')
            {
                DB::connection()->getPdo()->rollback();
            }
            $this->errors = new MessageBag(["model" => "L'utente esiste già."]);
            throw new UserExistsException;
        }

        if(App::environment() != 'testing')
        {
            DB::connection('authentication')->getPdo()->commit();
            // reactivate integrity check
            DB::connection('authentication')->getPdo()->exec('SET FOREIGN_KEY_CHECKS=1;');
        }
        return $user;
    }

    /**
     * @param array $input
     * @throws \Palmabit\Library\Exceptions\ValidationException
     */
    protected function validateInput(array $input)
    {
        if (!$this->v->validate($input))
        {
            $this->errors = $this->v->getErrors();
            throw new ValidationException;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
} 