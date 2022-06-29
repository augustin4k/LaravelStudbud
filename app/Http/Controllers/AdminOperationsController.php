<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Emails;

class AdminOperationsController extends Controller
{
    public function getInfo(Request $request)
    {
        $info = [];
        if ($request->tipInfo == 'users') {
            $info = User::select('id', 'name', 'surname', 'date_birth', 'email_verified_at', 'created_at')->where('id', '!=', auth()->user()->id)->get();
            foreach ($info as $key => $value) {
                $info[$key]->role = $value->roles()->select('name')->get();
            }
        } else if ($request->tipInfo == 'emails') {
            $info = Emails::orderBy('created_at', 'Desc')->get();
        }
        return ['info' => $info];
    }

    public function updateInfo(Request $request)
    {
        foreach ($request->selectedInfo as $key => $value) {
            if ($request->table == 'users') {
                if ($this->checkIfRoleIsMore($value['#ID']) == false) {
                    $user_finded = User::where('id', $value['#ID']);
                    if ($request->action == 'inactive') {
                        $user_finded->where('email_verified_at', '!=', null)
                            ->update(['email_verified_at' => null]);
                    } else if ($request->action == 'active')
                        $user_finded->where('email_verified_at', null)
                            ->update(['email_verified_at' => now()]);
                    else if ($request->action == 'UpdateAdmin') {
                        $user_finded = $user_finded->first();
                        if ($user_finded->hasRole('admin')) {
                            DB::table('role_user')->where('user_id', $user_finded->id)->delete();
                        } else {
                            $admin_id = Role::where('name', 'admin')->select('id')->first()->id;
                            DB::table('role_user')->insert([
                                'role_id' => $admin_id,
                                'user_id' => $user_finded->id,
                            ]);
                        }
                    }
                }
            } else if ($request->table == 'emails') {
                $emails_finded = Emails::where('id', $value['#ID']);
                if ($request->action == 'mark') {
                    if ($emails_finded->first()->indeplinit_de == null) {
                        $emails_finded->update([
                            'indeplinit_de' => auth()->user()->email,
                        ]);
                    } else {
                        $emails_finded->update([
                            'indeplinit_de' => null,
                        ]);
                    }
                }
            }
        }
        return;
    }
    public function deleteInfo(Request $request)
    {
        foreach ($request->info as $key => $value) {
            if ($request->table == 'users') {
                if ($this->checkIfRoleIsMore($value['#ID']) == false) {

                    $delete_elements = User::where('id', $value['#ID']);
                }
            } else {
                $delete_elements = Emails::where('id', $value['#ID']);
            }
            $delete_elements = $delete_elements->delete();
        }
        return;
    }

    public function checkIfArrayIsAllowedForDelete(Request $request)
    {
        foreach ($request->users as $key => $value) {
            // return $this->checkIfRoleIsMore($value['#ID']);
            if ($this->checkIfRoleIsMore($value['#ID']))
                return true;
        }
        return false;
    }

    public function getRoleOfUser()
    {
        return auth()->user()->roles()->select('name')->get();
    }


    // checking role of user for manipulation above him
    function convertRolesInMaxGrade($roles)
    {
        if (count($roles) == 0) return array(0);
        $dictionar = array(null, 'admin', 'Super admin');
        return array_map(fn ($value): int => array_search($value['name'], $dictionar), $roles->toArray());
    }
    function checkIfRoleIsMore($idOfUser)
    {
        $user = User::where('id', $idOfUser)->first();
        if (!empty($user))
            return max($this->convertRolesInMaxGrade(auth()->user()->roles()->select('name')->get())) <=
                max($this->convertRolesInMaxGrade($user->roles()->select('name')->get()));
        else return false;
    }
}
