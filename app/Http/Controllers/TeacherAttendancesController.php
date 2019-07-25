<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeacherAttendances;
use Illuminate\Support\Facades\DB;

class TeacherAttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teacher = DB::select('SELECT
            teacher_attendances.id,
            teacher_attendances.date,
            teacher_attendances.status,
            teachers.name
            FROM
            teacher_attendances
            INNER JOIN teachers ON teacher_attendances.teacher_id = teachers.id
            WHERE DATE(teacher_attendances.date) = CURDATE()
            ORDER BY
            teacher_attendances.id DESC');
        
        return response()->json($teacher);
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
        $teacherAttend = new teacherAttendances;
        $teacherAttend->teacher_id = $request->get('teacherId');
        $teacherAttend->date = $request->get('dateAttend');
        $teacherAttend->status = $request->get('status');
        $teacherAttend->save();

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
        $teacher = DB::select('SELECT
            teacher_attendances.id,
            teacher_attendances.date,
            teacher_attendances.status,
            teachers.name as teacher_name,
            teachers.id as teacher_id
            FROM
            teacher_attendances
            INNER JOIN teachers ON teacher_attendances.teacher_id = teachers.id
            WHERE
            teacher_attendances.id = "'.$id.'"');
        
        return response()->json($teacher);
    }

    public function filterDate(Request $request)
    {
        $teacher = DB::select('SELECT
            teacher_attendances.id,
            teacher_attendances.date,
            teacher_attendances.status,
            teachers.name
            FROM
            teacher_attendances
            INNER JOIN teachers ON teacher_attendances.teacher_id = teachers.id
            WHERE
            teacher_attendances.date BETWEEN "'.$request->get('date').' 00:00:00" AND "'.$request->get('date').' 23:59:59"');
        
        return response()->json($teacher);
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
        $teacherAttend = TeacherAttendances::find($id);
        $teacherAttend->teacher_id = $request->get('teacherId');
        $teacherAttend->date = $request->get('dateAttend');
        $teacherAttend->status = $request->get('status');
        $teacherAttend->save();

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
        $teacher = TeacherAttendances::find($id);
        $teacher->delete();

        return "Data berhasil dihapus";
    }
}
