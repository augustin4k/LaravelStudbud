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
        if ($input['tip_user'] === 'universitate') {
            if ($input['prezent-checked-institutie-single'] == 'on')
                $users->prezent_activity = true;
            else
                $users->prezent_activity = false;
            if ($input['prezent-checked-institutie-single'] == 'on') {
                $users->date_finish =  date('Y-m-d');
            } else
                $users->date_finish = $input['an-finish-institutie'];
        }
        $users->save();

        // create directory for photos of user
        File::makeDirectory(public_path() . '/img/users_photos/user_' . $users->id);
        $extension = $input['avatar-image']->getClientOriginalExtension();
        $file_name = rand() . time() . '.' . $extension;
        $input['avatar-image']->move(public_path() . '/img/users_photos/user_' . $users->id, $file_name);

        // editare avatar path, deoarece la inserare nu aveam id-ul noului user inserat, am pus o valoare default care ulterior am schimbat-o
        $edit_path = user::where('id', $users->id)->get()[0];
        $edit_path->avatar_path = '/img/users_photos/user_' . $users->id . '/' . $file_name;
        $edit_path->save();

        $password_reset = new password_resets();
        $password_reset->email = $input['email'];
        $password_reset->token = md5(rand(1, 10) . microtime());
        $password_reset->save();
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

        // logare user
        if ($users) {
            event(new Registered($users));
            Auth::login($users);
            return  redirect()->route('verification.notice');
        }

        return redirect()->back()->withErrors(['esuare-logare' => 'Nu te-am putut inregistra, au aparut careva probleme!']);
    }
}
