<?php  namespace Palmabit\Authentication\Services;

use Illuminate\Support\Facades\App;
use Config, Redirect, DB;
use Illuminate\Support\MessageBag;
use Palmabit\Authentication\Exceptions\UserExistsException;
use Palmabit\Authentication\Models\User;
use Palmabit\Authentication\Validators\UserSignupValidator;
use Palmabit\Library\Exceptions\ValidationException;

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
        $this->p_r = App::make('profile_repository');
        $this->v = $v ? $v : new UserSignupValidator;
    }

    public function register(array $input)
    {
            $this->checkIfExistRegisteredUserWithRecivedEmail($input);

        // for default user is not active at registration
        $input["activated"] = false;

        $this->validateInput($input);

        $user = $this->saveDbData($input);

        $mailer = App::make('palmamailer');
        $this->sendMailToClient($mailer, $input);
        $this->sendMailToAdmins($mailer, $user, $input);
    }

    /**
     * @param $mailer
     * @param $input
     */
    protected function sendMailToClient($mailer, $input)
    {
        // send email to client
        $mailer->sendTo($input['email'], ["email" => $input["email"], "password" => $input["password"]],
            "Registration request on: " . \Config::get('authentication::app_name'), "authentication::mail.registration-waiting-client");
    }

    /**
     * @param $mailer
     * @param $user
     * @param $input
     */
    protected function sendMailToAdmins($mailer, $user, $input)
    {
        // send email to admins
        $mail_helper = App::make('authentication_helper');
        $mails = $mail_helper->getNotificationRegistrationUsersEmail();
        if (!empty($mails)) {
            foreach ($mails as $mail) {
                $mailer->sendTo($mail, ["email" => $input["email"], "id" => $user->id,
                    "comments" => $input['comments']], "User signup request", "authentication::mail.registration-waiting-admin");
            }
        }
    }

    /**
     * Send activation email to the client if it's getting activated
     *
     * @param $obj
     * @param $input
     */
    public function sendActivationEmailToClient($obj, array $input)
    {
        $mailer = App::make('palmamailer');
        // if i activate a deactivated user
        if (isset($input["activated"]) && $input["activated"] && (!$obj->activated)) {
            $mailer->sendTo($obj->email, ["email" => $obj->email],
                "You are activated on " .
                Config::get('authentication::app_name'), "authentication::mail.registration-activated-client");
        }
    }

    /**
     * @param array $input
     * @return mixed
     * @throws \Palmabit\Authentication\Exceptions\UserExistsException
     */
    protected function saveDbData(array $input)
    {
        if (App::environment() != 'testing') {
            // temporary disable reference integrity check
            DB::connection('authentication')->getPdo()->exec('SET FOREIGN_KEY_CHECKS=0;');
            DB::connection('authentication')->getPdo()->beginTransaction();
        }
        try {
            // user
            $user = $this->u_r->create($input);
            // profile
            $this->p_r->create(array_merge(["user_id" => $user->id], $input));
        } catch (UserExistsException $e) {
            if (App::environment() != 'testing') {
                DB::connection()->getPdo()->rollback();
            }
            $this->errors = new MessageBag(["model" => "The user already exists."]);
            throw new UserExistsException;
        }

        if (App::environment() != 'testing') {
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
        if (!$this->v->validate($input)) {
            $this->errors = $this->v->getErrors();
            throw new ValidationException;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function checkIfExistRegisteredUserWithRecivedEmail($input)
    {
        $users = User::where('email', '=', $input['email'])->get();
        if (count($users) != 0) {
            $this->errors = new MessageBag(['This email is already in use']);
            throw new UserExistsException;
        }

    }
} 