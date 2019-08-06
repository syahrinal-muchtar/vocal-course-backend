<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employees;
use App\UsersBranches;
use App\UsersGroups;
use App\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = DB::select('SELECT
            employees.id,
            employees.`name`,
            employees.no_hp,
            employees.address,
            employees.gender,
            employees.birthdate,
            employees.user_id,
            users.`status`
            FROM
            employees
            INNER JOIN users ON employees.user_id = users.id
            ORDER BY
            employees.id DESC');

        return response()->json([
            'message' => 'success',
            'data' => $employees
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new Users;
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        $user->password = Hash::make($request->get('password'));
        $user->note = $request->get('note');
        $user->joined_at = date("Y-m-d");
        $user->status = 1; //langsung aktif
        $user->save();

        $userGroup = new UsersGroups;
        $userGroup->user_id = $user->id;
        $userGroup->group_id = 2;
        $userGroup->save();
        
        $employee = new Employees;
        $employee->name = $request->get('name');
        $employee->no_hp = $request->get('noHp');
        $employee->address = $request->get('address');
        $employee->gender = $request->get('gender');
        $employee->birthdate = $request->get('birthdate');
        $employee->user_id = $user->id;
        $employee->save();

        foreach ($request->get('branch') as $branch) {
            $userBranch = new UsersBranches;
            $userBranch->user_id = $user->id;
            $userBranch->branch_id = $branch['value'];
            $userBranch->save();
        }

        return "Data berhasil masuk";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = DB::select('SELECT
            employees.id,
            employees.`name` AS employee_name,
            employees.no_hp,
            employees.address,
            employees.gender,
            employees.birthdate
            FROM
            employees
            WHERE
            employees.id = "'.$id.'"');
        
        return response()->json($employee);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $employee = Employees::find($id);
        $employee->name = $request->get('name');
        $employee->no_hp = $request->get('noHp');
        $employee->address = $request->get('address');
        $employee->gender = $request->get('gender');
        $employee->birthdate = $request->get('birthdate');
        $employee->save();

        return "Data berhasil di update";    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employees::find($id);
        $employee->delete();

        return "Data berhasil dihapus";
    }
}
