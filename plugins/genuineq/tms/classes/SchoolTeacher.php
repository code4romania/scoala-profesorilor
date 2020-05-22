<?php namespace Genuineq\Tms\Classes;

use URL;
use Auth;
use Lang;
use Mail;
use Flash;
use Validator;
use alcea\cnp\Cnp;
use Genuineq\User\Models\User;
use Genuineq\User\Models\UserGroup;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\ContractType;
use Genuineq\User\Models\Settings as UserSettings;

class SchoolTeacher
{
    /**
     * Function that creates a teacher and connects it with a school.
     *
     * @param $newData Array containing the data used for creating a new school profile.
     *
     * @return boolean
     */
    public static function createSchoolTeacher(array $newData)
    {
        /** Check if the user already exists. */
        $user = User::whereEmail($newData['email'])->whereIdentifier($newData['identifier'])->first();

        if ($user) {
            /** The user exists, just make the connection IF NEEDED. */
            $teacher = $user->profile;

            /** Check if the connection DOESN'T already exists. */
            if (!$teacher->schools || !$teacher->schools->find(Auth::user()->profile->id)) {
                /** Make the connection. */
                $teacher->schools()->attach(Auth::user()->profile);
                $teacher->reloadRelations('schools');
            }

            return true;
        } else {
            /** Extract the user data. */
            $data = [
                'name' => $newData['name'],
                'email' => $newData['email'],
                'identifier' => $newData['identifier'],
            ];

            /** Extract the validation rules. */
            $rules = [
                'name' => ['required', 'regex:/^[a-zA-Z0123456789 -]*$/i'],
                'email' => 'required|between:6,255|email|unique:users',
                'identifier' => 'required|unique:users',
            ];

            /** Apply the validation rules. */
            $validation = Validator::make($data, $rules);
            if ($validation->fails() || !Cnp::validate($newData['identifier'])) {
                return false;
            }

            /** Create the teacher user. */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'identifier' => $data['identifier'],
                'type' => 'teacher',
                'password' => str_random(16),
            ]);

            /** Add the user to the teacher group. */
            $user->addGroup(UserGroup::getGroup('teacher'));

            /** Create a new user profile. */
            $teacher = new Teacher();

            $teacher->name = $newData['name'];
            $teacher->slug = str_replace(' ', '-', strtolower($newData['name']));
            $teacher->phone = $newData['phone'];
            $teacher->birth_date = date('Y-m-d H:i:s', strtotime($newData['birth_date']));
            $teacher->description = $newData['description'];

            $fullAddress = explode(', ', $newData['address']);
            $address = Address::whereName($fullAddress[0])->whereCounty($fullAddress[1])->first();
            $teacher->address_id = ($address) ? ($address->id) : ('');

            $seniorityLevel = SeniorityLevel::whereName($newData['seniority_level'])->first();
            $teacher->seniority_level_id = ($seniorityLevel) ? ($seniorityLevel->id) : ('');

            $schoolLevel = SchoolLevel::whereName($newData['school_level'])->first();
            $teacher->school_level_id = ($schoolLevel) ? ($schoolLevel->id) : ('');

            $contractType = ContractType::whereName($newData['contract_type'])->first();
            $teacher->contract_type_id = ($contractType) ? ($contractType->id) : ('');
            $teacher->user_id = $user->id;

            $teacher->save();

            $teacher->schools()->attach(Auth::user()->profile);
            $teacher->reloadRelations('schools');

            /** Send activation email if the activation is configured to be performed by the user */
            if (UserSettings::ACTIVATE_USER == UserSettings::get('activate_mode')) {
                self::sendActivationEmail($user);
            }

            return true;
        }
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Sends the activation email to a user
     * @param  User $user
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
            'link' => $link,
            'code' => $code
        ];

        Mail::send('genuineq.user::mail.activate', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }
}
