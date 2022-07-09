<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Models\password_resets;
use App\Models\Activities;

class RegisterController extends Controller
{

    public function submit(RegisterRequest $req)
    {

        if (Auth::check()) {
            return redirect()->back();
        }

        $input = $req->all();
        //  insert user table
        $users = new User();
        $users->name = $input['Numele'];
        $users->surname = $input['Prenumele'];
        $users->date_birth = $input['Data_nasterii'];
        $users->country = $input['Tara'];
        $users->city = $input['Orasul'];
        $users->avatar_path = 'null';
        $users->email = $input['email'];
        $users->password = Hash::make($input['password']);
        $users->user_type = $input['tip_user'];
        $this->universitateFill($input, $users);
        $users->save();

        // create directory for photos of user
        File::makeDirectory(public_path() . '/img/users_photos/user_' . $users->id);

        $file_name = '';
        $this->uploadAvatar($users, $input, $file_name);

        // editare avatar path, deoarece la inserare nu aveam id-ul noului user inserat, am pus o valoare default care ulterior am schimbat-o
        $edit_path = user::where('id', $users->id)->get()[0];
        $edit_path->avatar_path =  $file_name;
        $edit_path->save();

        $password_reset = new password_resets();
        $password_reset->email = $input['email'];
        $password_reset->token = md5(rand(1, 10) . microtime());
        $password_reset->save();
        $this->saveActivities($input, $req, $users);
        // logare user
        if ($users) {
            event(new Registered($users));
            Auth::login($users);
            return  redirect()->route('verification.notice');
        }

        return redirect()->back()->withErrors(['esuare-logare' => 'Nu te-am putut inregistra, au aparut careva probleme!']);
    }

    // helpers 
    function universitateFill($input, $users)
    {
        if ($input['tip_user'] === 'universitate') {
            if (array_key_exists('prezent-checked-institutie-single', $input) && $input['prezent-checked-institutie-single'] == 'on') {

                $users->prezent_activity = true;
                $users->date_finish =  date('Y-m-d');
            } else {
                $users->prezent_activity = false;
                $users->date_finish = $input['an-finish-institutie'];
            }
        }
    }
    function saveActivities($input, $req, $users)
    {
        // insert activities table if needed
        if ($input['tip_user'] == 'student1' || $input['tip_user'] == 'profesor') {
            for ($i = 1; $i <= $input['nr-institutii'][$input['tip_user']]; $i++) {
                $activities = new Activities();
                $activities->name_institution = $input['nume-institutie'][$input['tip_user']][$i];
                $activities->date_start = $input['start-studii-institutie'][$input['tip_user']][$i];
                echo $input['start-studii-institutie'][$input['tip_user']][$i] . " ";
                if ($req->has('prezent-checked-institution.' . $input['tip_user'] . '.' . $i)) {
                    $activities->date_finish = date('Y-m-d');
                    $activities->prezent_activity = true;
                } else {
                    $activities->date_finish = $input['finish-studii-institutie'][$input['tip_user']][$i];
                    $activities->prezent_activity = false;
                }
                $activities->specialization = $input['specialitate-user'][$input['tip_user']][$i];
                $activities->grade = $input['nivel-user'][$input['tip_user']][$i];
                $activities->country = $input['tara-institutie'][$input['tip_user']][$i];
                $activities->city = $input['orasul-institutie'][$input['tip_user']][$i];
                $activities->user_id = $users->id;
                $activities->save();
            }
        }
    }
    function uploadAvatar($users, $input, &$file_name)
    {
        $extension = $input['avatar-image']->getClientOriginalExtension();
        $file_name = rand() . time() . '.' . $extension;
        $input['avatar-image']->move(public_path() . '/img/users_photos/user_' . $users->id, $file_name);
        $file_name = '/img/users_photos/user_' . $users->id . '/' . $file_name;
    }

    // SETTINGS
    public function get_info_settings(Request $req)
    {
        if ($req->type == 'personal_data')
            return ['personalData' => Auth::user()];
        else if ($req->type == 'activities_data') {
            if (Auth::user()->user_type != 'student0' && Auth::user()->user_type != 'universitate') {
                return User::where('id', Auth::user()->id)->first()->activities()->get();
            }
        }
    }
    public function update_settings(RegisterRequest $req)
    {
        $input = $req->all();

        $dictionar = [
            'name' => 'Numele',
            'surname' => 'Prenumele',
            'date_birth' => 'Data_nasterii',
            'city' => 'Orasul',
            'country' => 'Tara',
            'user_type' => 'tip_user',
        ];
        $user = User::where('id', Auth::user()->id);
        foreach ($dictionar as $key => $value) {
            if (Auth::user()->$key != $input[$value]) {
                $user->update([$key => $input[$value]]);
            }
        }

        $universityFill = $user->first();
        $this->universitateFill($input, $universityFill);
        $universityFill->save();

        if (array_key_exists('avatar-image', $input)) {
            if (file_exists(Auth::user()->avatar_path)) {
                unlink(Auth::user()->avatar_path);
            }

            $file_name = '';
            $this->uploadAvatar(Auth::user(), $input, $file_name);

            $user->update(['avatar_path' =>  $file_name]);
        }
        $activities = $user->first()->activities();
        if ($activities->get()) {
            $activities->delete();
        }
        $this->saveActivities($input, $req, Auth::user());
        return redirect()->back()->with('message_success', 'settings');
    }
}
