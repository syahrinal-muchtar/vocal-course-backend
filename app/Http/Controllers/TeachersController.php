<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teachers;
use App\UsersBranches;
use App\UsersGroups;
use App\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teachers = Teachers::orderBy('id', 'desc')->get();

            return response()->json([
            'message' => 'success',
            'data' => $teachers
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
        $user->save();

        $userGroup = new UsersGroups;
        $userGroup->user_id = $user->id;
        $userGroup->group_id = 1;
        $userGroup->save();

        $teacher = new Teachers;
        $teacher->name = $request->get('name');
        $teacher->salary = $request->get('salary');
        $teacher->save();

        foreach ($request->get('branch') as $branch) {
            $userBranch = new UsersBranches;
            $userBranch->user_id = $user->id;
            $userBranch->branch_id = $branch['value'];
            $userBranch->save();
        }

        return 'success';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $teacher = Teachers::find($id);
        return response()->json($teacher);
    }

    public function filterStudent(Request $request)
    {
        $teachers = DB::select('SELECT
            teachers.`name`,
            teachers.id
            FROM
            students
            INNER JOIN teachers ON students.teacher_id = teachers.id
            WHERE
            students.id = "'.$request->get('student').'"');

        return response()->json([
            'message' => 'success',
            'data' => $teachers
        ], 200);
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
        $teacher = Teachers::find($id);  
        $teacher->name = $request->get('name');
        $teacher->salary = $request->get('salary');

        $teacher->save();

        return "Data berhasil di Update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $teacher = Teachers::find($id);
        $teacher->delete();

        return "Data berhasil dihapus";
    }
}
