<?php namespace Genuineq\User\Backend;

use Lang;
use Mail;
use Flash;
use Backend;
use BackendAuth;
use Validator;
use ValidationException;

/**
 * Class responsible of overwriting the backend restore password default flow.
 */
class Restore
{
    /** Submits the restore form. */
    public static function initPasswordReset()
    {
        $rules = [
            'login' => 'required|between:2,255'
        ];

        $validation = Validator::make(post(), $rules);
        if (!$validation->fails()) {
            /** Search for a backend user with the specified username. */
            $user = BackendAuth::findUserByLogin(post('login'));

            if ($user) {
                $code = $user->getResetPasswordCode();
                $link = Backend::url('backend/auth/reset/' . $user->id . '/' . $code);

                $data = [
                    'name' => $user->full_name,
                    'link' => $link,
                ];

                Mail::send('backend::mail.restore', $data, function ($message) use ($user) {
                    $message->to($user->email, $user->full_name)->subject(trans('backend::lang.account.password_reset'));
                });
            }
        }

        Flash::success(Lang::get('genuineq.user::lang.backend.restore.password_restore_success'));
    }
}
