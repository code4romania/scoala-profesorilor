<?php namespace Genuineq\User\Helpers;

use URL;
use Mail;

class EmailHelper
{
    /**
     * Sends the activation email to a user
     * @param  Genuineq\User\Models\User $user
     * @return void
     */
    public static function sendActivationEmail($user)
    {
        /** Generate an activation code. */
        $code = implode('!', [$user->id, $user->getActivationCode()]);
        /** Create the activation URL. */
        $link = URL::to('/') . '?activate=' . $code;

        $data = [
            'name' => $user->name,
            'link' => $link
        ];

        Mail::send('genuineq.user::mail.activate', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }

    /**
     * Sends the teacher new user email
     * @param  Genuineq\User\Models\User $user
     * @param  Genuineq\Tms\Models\School $school
     * @return void
     */
    public static function sendNewUserEmail($user, $school)
    {
        /** Generate a password reset code. */
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        /** Create the password reset URL. */
        $link = URL::to('/') . '?reset=' . $code;

        $data = [
            'teacher_name' => $user->name,
            'school_name' => $school->name,
            'link' => $link
        ];

        Mail::send('genuineq.user::mail.new_user', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }

    /**
     * Sends the welcome email to a user
     * @param  Genuineq\User\Models\User $user
     * @return void
     */
    public static function sendWelcomeEmail($user)
    {
        /** Generate a password reset code. */
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        /** Create the password reset URL. */
        $link = URL::to('/') . '?reset=' . $code;

        $data = [
            'name' => $user->name,
            'login_link' => URL::to('/'),
            'pass_reset_link' => $link
        ];

        Mail::send('genuineq.user::mail.welcome', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }

    /**
     * Sends the invvite email to a user
     * @param  Genuineq\User\Models\User $user
     * @return void
     */
    public static function sendInviteEmail($user)
    {
        /** Generate a password reset code. */
        $code = implode('!', [$user->id, $user->getResetPasswordCode()]);
        /** Create the password reset URL. */
        $link = URL::to('/') . '?reset=' . $code;

        $data = [
            'name' => $user->name,
            'link' => $link
        ];

        Mail::send('genuineq.user::mail.invite', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }
}
