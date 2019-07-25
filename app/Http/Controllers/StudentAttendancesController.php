<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudentAttendances;
use App\Classes;
use Illuminate\Support\Facades\DB;

class StudentAttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $classes = Classes::all();
        $students = array();

        foreach ($classes as $class) {
            $student = array();
            $student = DB::select('SELECT
            student_attendances.id,
            student_attendances.date,
            student_attendances.`status`,
            students.first_name,
            students.middle_name,
            students.last_name,
            classes.`name` as class_name
            FROM
            student_attendances
            INNER JOIN students ON student_attendances.student_id = students.id
            INNER JOIN classes ON students.class_id = classes.id
            WHERE 
            DATE(student_attendances.date) = CURDATE()
            AND
            classes.`name` = "'.$class->name.'"
            ORDER BY
            student_attendances.id DESC');
            array_push($students, $student != [] ? $student : $class->name);

            // $student = DB::select('SELECT
// if(student_attendances.date BETWEEN '2019-05-26 00:00:00' AND '2019-05-26 23:59:59', student_attendances.date, NULL) as date,
// schedules.`day`,
// schedules.time,
// students.first_name,
// students.middle_name,
// students.last_name,
// if(student_attendances.date BETWEEN '2019-05-26 00:00:00' AND '2019-05-26 23:59:59', student_attendances.`status`, NULL) as statuss
// FROM
// student_attendances
// RIGHT JOIN schedules ON student_attendances.schedule_id = schedules.id
// INNER JOIN students ON schedules.student_id = students.id);
        }
        
        return response()->json($students);
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
        $studentAttend = new StudentAttendances;
        $studentAttend->student_id = $request->get('studentId');
        $studentAttend->date = $request->get('dateAttend');
        $studentAttend->status = $request->get('status');
        $studentAttend->save();

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
        $student = DB::select('SELECT
            student_attendances.id,
            student_attendances.date,
            student_attendances.status,
            students.first_name,
            students.middle_name,
            students.last_name,
            students.id as student_id
            FROM
            student_attendances
            INNER JOIN students ON student_attendances.student_id = students.id
            WHERE
            student_attendances.id = "'.$id.'"');
        
        return response()->json($student);
    }

    public function filterDate(Request $request)
    {
        $classes = Classes::all();
        $students = array();

        foreach ($classes as $class) {
            $student = array();
            $student = DB::select('SELECT
                student_attendances.id,
                student_attendances.date,
                student_attendances.`status`,
                students.first_name,
                students.middle_name,
                students.last_name,
                classes.`name` as class_name
                FROM
                student_attendances
                INNER JOIN students ON student_attendances.student_id = students.id
                INNER JOIN classes ON students.class_id = classes.id
                WHERE 
                student_attendances.date BETWEEN "'.$request->get('date').' 00:00:00" AND "'.$request->get('date').' 23:59:59"
                AND
                classes.`name` = "'.$class->name.'"
                ORDER BY
                student_attendances.id DESC');
            array_push($students, $student != [] ? $student : $class->name);
        }
        
        return response()->json($students);
    }

    public function filterClass(Request $request)
    {
        $student = DB::select('SELECT
            student_attendances.id,
            student_attendances.date,
            student_attendances.`status`,
            students.first_name,
            students.middle_name,
            students.last_name
            classes.`name` as class_name
            FROM
            student_attendances
            INNER JOIN students ON student_attendances.student_id = students.id
            INNER JOIN classes ON students.class_id = classes.id
            WHERE 
            DATE(student_attendances.date) = CURDATE()
            AND
            classes.`name` = "'.$request->get('class').'"
            ORDER BY
            student_attendances.id DESC');
        
        return response()->json($student);
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
        $studentAttend = StudentAttendances::find($id);
        $studentAttend->student_id = $request->get('studentId');
        $studentAttend->date = $request->get('dateAttend');
        $studentAttend->status = $request->get('status');
        $studentAttend->save();

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
        $student = StudentAttendances::find($id);
        $student->delete();

        return "Data berhasil dihapus";
    }
}
