<?php namespace Genuineq\Tms\Classes;

use Log;
use URL;
use Auth;
use Lang;
use Mail;
use Flash;
use Validator;
use alcea\cnp\Cnp;
use Genuineq\User\Models\User;
use Genuineq\Tms\Models\Teacher;
use Genuineq\Tms\Models\Address;
use Genuineq\Tms\Models\Appraisal;
use Genuineq\User\Models\UserGroup;
use Genuineq\Tms\Models\LearningPlan;
use Genuineq\Tms\Models\Grade;
use Genuineq\Tms\Models\SchoolLevel;
use Genuineq\Tms\Models\ContractType;
use Genuineq\Tms\Models\SeniorityLevel;
use Genuineq\Tms\Models\Specialization;
use Genuineq\User\Models\Settings as UserSettings;

class SchoolTeacher
{
    /**
     * Function used for creating a single teacher and connect it with a school.
     *
     * @param $newData Array containing the data used for creating a new school profile.
     *
     * @return array
     */
    public static function createSingleSchoolTeacher(array $newData)
    {
        /** Extarct the current school. */
        $school = Auth::user()->profile;

        /** Check if the user already exists. */
        $user = User::where(function ($query) use ($newData) {
            $query->where('email', $newData['email'])
                  ->where('type', 'teacher');
        })->orWhere(function ($query) use ($newData) {
            $query->where('identifier', $newData['identifier'])
                  ->where('type', 'teacher');
        })->first();

        if ($user) {
            /** The user exists, just make the connection IF NEEDED. */
            $teacher = $user->profile;

            /** Check if the connection DOESN'T already exists. */
            if (!$teacher->schools || !$teacher->schools->find($school->id)) {
                /** Make the school-teacher connection. */
                $teacher->schools()->attach($school);
                $teacher->reloadRelations('schools');

                /** Archive teacher learning plan if this is the first school-teacher connection */
                if (1 == $teacher->schools->count()) {
                    $teacher->active_learning_plan->archive();

                    $teacher->newLearningPlan();
                }

                /** Create appraisal for the new connection. */
                $appraisal = new Appraisal();
                $appraisal->school_id = $school->id;
                $appraisal->teacher_id = $teacher->id;
                $appraisal->save();

                return [
                    'value' => 1,
                    'status' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_association_successful')
                ];
            }

            return [
                'value' => 2,
                'status' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_association_exists')
            ];
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

            /** Construct the validation error messages. */
            $messages = [
                'name.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.name_required'),
                'name.regex' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.name_alpha'),
                'email.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.email_required'),
                'email.between' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.email_between'),
                'email.email' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.email_email'),
                'email.unique' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.email_unique'),
                'identifier.required' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.sid_required'),
                'identifier.unique' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.validation.sid_unique'),
            ];

            /** Apply the validation rules. */
            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                return [
                    'value' => 3,
                    'status' => $validation
                ];
            }

            /** Extra validate the SID unique identifier field. */
            if (!Cnp::validate($newData['identifier'])) {
                return [
                    'value' => 4,
                    'status' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.identifier_invalid')
                ];
            }

            /** Create the teacher user. */
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'identifier' => $data['identifier'],
                'type' => 'teacher',
                'password' => str_random(16),
            ]);

            /** Activate the new created teacher user. */
            $user->is_activated = true;
            $user->activated_at = $user->freshTimestamp();
            $user->forceSave();

            /** Add the user to the teacher group. */
            $user->addGroup(UserGroup::getGroup('teacher'));


            /** Get the address. */
            $fullAddress = explode(', ', $newData['address']);
            $address = Address::where('name', $fullAddress[0])->where('county', $fullAddress[1])->first();
            /** Get the seniority level */
            $seniorityLevel = SeniorityLevel::whereName($newData['seniority_level'])->first();

            /** Populate the new user profile. */
            $teacher = $user->profile;
            $teacher->name = $newData['name'];
            $teacher->slug = str_replace(' ', '-', strtolower($newData['name']));
            $teacher->phone = $newData['phone'];
            $teacher->birth_date = date('Y-m-d H:i:s', strtotime($newData['birth_date']));
            $teacher->address_id = ($address) ? ($address->id) : ('');
            $teacher->description = $newData['description'];
            $teacher->user_id = $user->id;
            $teacher->seniority_level_id = ($seniorityLevel) ? ($seniorityLevel->id) : ('');
            $teacher->save();

            /** Make the school-teacher connection. */
            $teacher->schools()->attach(
                $school,
                [
                    'contract_type_id' => (ContractType::whereName($newData['contract_type'])->first()) ? (ContractType::whereName($newData['contract_type'])->first()->id) : (null),
                    'school_level_id' => (SchoolLevel::whereName($newData['school_level'])->first()) ? (SchoolLevel::whereName($newData['school_level'])->first()->id) : (null),
                    'grade_id' => (Grade::whereName($newData['grade'])->first()) ? (Grade::whereName($newData['grade'])->first()->id) : (null),
                    'specialization_1_id' => (Specialization::whereName($newData['specialization_1'])->first()) ? (Specialization::whereName($newData['specialization_1'])->first()->id) : (null),
                    'specialization_2_id' => (Specialization::whereName($newData['specialization_2'])->first()) ? (Specialization::whereName($newData['specialization_2'])->first()->id) : (null),
                ]
            );
            $teacher->reloadRelations('schools');

            /** Create appraisal for the new connection. */
            $appraisal = new Appraisal();
            $appraisal->school_id = $school->id;
            $appraisal->teacher_id = $teacher->id;
            $appraisal->save();

            /** Send activation email. */
            self::sendActivationEmail($user, $school);

            return [
                'value' => 1,
                'status' => Lang::get('genuineq.tms::lang.component.school-teacher-profile.message.teachers_import_add_successful')
            ];
        }
    }

    /**
     * Function that creates a teacher and connects it with a school.
     *
     * @param $newData Array containing the data used for creating a new school profile.
     *
     * @return boolean
     */
    public static function createSchoolTeacher(array $newData)
    {
        /** Extarct the current school. */
        $school = Auth::user()->profile;

        /** Check if the user already exists. */
        $user = User::whereEmail($newData['email'])->whereIdentifier($newData['identifier'])->first();

        if ($user) {
            /** The user exists, just make the connection IF NEEDED. */
            $teacher = $user->profile;

            /** Check if the connection DOESN'T already exists. */
            if (!$teacher->schools || !$teacher->schools->find($school->id)) {
                /** Make the school-teacher connection. */
                $teacher->schools()->attach($school);
                $teacher->reloadRelations('schools');

                /** Archive teacher learning plan if this is the first school-teacher connection */
                if (1 == $teacher->schools->count()) {
                    $teacher->active_learning_plan->archive();

                    $teacher->newLearningPlan();
                }

                /** Create appraisal for the new connection. */
                $appraisal = new Appraisal();
                $appraisal->school_id = $school->id;
                $appraisal->teacher_id = $teacher->id;
                $appraisal->save();
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

            /** Activate the new created teacher user. */
            $user->is_activated = true;
            $user->activated_at = $user->freshTimestamp();
            $user->forceSave();

            /** Add the user to the teacher group. */
            $user->addGroup(UserGroup::getGroup('teacher'));

            /** Get the address. */
            $fullAddress = explode(', ', $newData['address']);
            $address = Address::whereName($fullAddress[0])->whereCounty($fullAddress[1])->first();
            /** Get the seniority level */
            $seniorityLevel = SeniorityLevel::whereName($newData['seniority_level'])->first();

            /** Populate the new user profile. */
            $teacher = $user->profile;
            $teacher->name = $newData['name'];
            $teacher->slug = str_replace(' ', '-', strtolower($newData['name']));
            $teacher->phone = $newData['phone'];
            $teacher->birth_date = date('Y-m-d H:i:s', strtotime($newData['birth_date']));
            $teacher->address_id = ($address) ? ($address->id) : ('');
            $teacher->description = $newData['description'];
            $teacher->user_id = $user->id;
            $teacher->seniority_level_id = ($seniorityLevel) ? ($seniorityLevel->id) : ('');
            $teacher->save();

            /** Make the school-teacher connection. */
            $teacher->schools()->attach(
                $school,
                [
                    'contract_type_id' => (ContractType::whereName($newData['contract_type'])->first()) ? (ContractType::whereName($newData['contract_type'])->first()->id) : (null),
                    'school_level_id' => (SchoolLevel::whereName($newData['school_level'])->first()) ? (SchoolLevel::whereName($newData['school_level'])->first()->id) : (null),
                    'grade_id' => (Grade::whereName($newData['grade'])->first()) ? (Grade::whereName($newData['grade'])->first()->id) : (null),
                    'specialization_1_id' => (Specialization::whereName($newData['specialization_1'])->first()) ? (Specialization::whereName($newData['specialization_1'])->first()->id) : (null),
                    'specialization_2_id' => (Specialization::whereName($newData['specialization_2'])->first()) ? (Specialization::whereName($newData['specialization_2'])->first()->id) : (null),
                ]
            );
            $teacher->reloadRelations('schools');

            /** Create appraisal for the new connection. */
            $appraisal = new Appraisal();
            $appraisal->school_id = $school->id;
            $appraisal->teacher_id = $teacher->id;
            $appraisal->save();

            /** Send activation email. */
            self::sendActivationEmail($user, $school);

            return true;
        }
    }

    /***********************************************
     ******************* Helpers *******************
     ***********************************************/

    /**
     * Sends the teacher activation email
     * @param  Genuineq\User\Models\User $user
     * @param  Genuineq\Tms\Models\School $school
     * @return void
     */
    public static function sendActivationEmail($user, $school)
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

        Mail::send('genuineq.user::mail.invite', $data, function($message) use ($user) {
            $message->to($user->email, $user->name);
        });
    }
}
