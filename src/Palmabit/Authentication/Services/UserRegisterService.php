<?php  namespace Palmabit\Authentication\Services;
use Illuminate\Support\Facades\App;
use Config;
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

    public function __construct()
    {
        $this->u_r = App::make('user_repository');
        $this->p_r = App::make('profile_repository');
    }

    public function register(array $input)
    {
        // for default user is not active at registration
        $input["activated"] = false;

        $user = $this->saveDbData($input);

        $mailer = App::make('palmamailer');
        $this->sendMailToClient($mailer, $user);
        $this->sendMailToAdmins($mailer);
    }

    /**
     * @param $mailer
     * @param $user
     */
    protected function sendMailToClient($mailer, $user)
    {
        // send email to client
        $mailer->sendTo($user->email, "", "", "authentication::mail.registration-waiting-client");
    }

    /**
     * @param $mailer
     */
    protected function sendMailToAdmins($mailer)
    {
        // send email to admins
        $mail_helper = App::make('authentication_helper');
        $mails       = $mail_helper->getNotificationRegistrationUsersEmail();
        if (!empty($mails)) foreach ($mails as $mail)
        {
            $mailer->sendTo($mail, "", "", "authentication::mail.registration-waiting-admin");
        }
    }

    /**
     * Send activation email to the client if it's getting activated
     * @param $obj
     */
    public function sendActivationEmailToClient($obj, array $input)
    {
        $mailer = App::make('palmamailer');
        // if i activate a deactivated user
        if($input["activated"] && (! $obj->activated) ) $mailer->sendTo($obj->email, "", "Sei stato attivato su ".Config::get('authentication::app_name'), "authentication::mail.registration-activated-client");
    }

    /**
     * @param array $input
     * @return mixed
     */
    protected function saveDbData(array $input)
    {
        // user
        $user    = $this->u_r->create($input);
        // group
        $this->u_r->addGroup($user->id, $input["group_id"]);
        // profile
        $profile = $this->p_r->create(array_merge(["user_id" => $user->id], $input) );

        return $user;
    }
} 