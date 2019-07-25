<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedules;
use Illuminate\Support\Facades\DB;

class SchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schedules = DB::select('SELECT
            schedules.id,
            schedules.`day`,
            TIME_FORMAT(schedules.time, "%H:%i") as time,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.`name` as teacher,
            classes.`name` as class,
            rooms.id as room_id,
            rooms.name AS room_name
            FROM
            schedules
            INNER JOIN rooms ON schedules.room_id = rooms.id
            INNER JOIN students ON schedules.student_id = students.id
            INNER JOIN teachers ON schedules.teacher_id = teachers.id
            INNER JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "'.$request->get('branch').'"
            ORDER BY
            schedules.id desc');

        return response()->json($schedules);
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
        $checkSchedule = DB::select('SELECT
            schedules.id
            FROM
            schedules
            WHERE
            schedules.`day` = "'.$request->get('day').'" AND
            schedules.student_id = "'.$request->get('student').'"
            ');

        if(empty($checkSchedule))
        {
            $schedule = new Schedules;
            $schedule->day = $request->get('day');
            $schedule->time = $request->get('time');
            $schedule->student_id = $request->get('student');
            $schedule->teacher_id = $request->get('teacher');
            $schedule->room_id = $request->get('room');
            $schedule->class_id = $request->get('class');
            $schedule->branch_id = $request->get('branch');
            $schedule->save();

            return "Data berhasil masuk";
        }
        
        return response()->json([
            'message' => 'Duplicate'
        ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
            $schedule = DB::select('SELECT
                schedules.id,
                schedules.`day`,
                TIME_FORMAT(schedules.time, "%H:%i") as time,
                students.id as student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id as teacher_id,
                teachers.`name` as teacher_name,
                rooms.id as room_id,
                rooms.name AS room_name,
                classes.id as class_id,
                classes.`name` as class_name
                FROM
                schedules
                INNER JOIN rooms ON schedules.room_id = rooms.id
                INNER JOIN students ON schedules.student_id = students.id
                INNER JOIN teachers ON schedules.teacher_id = teachers.id
                INNER JOIN classes ON schedules.class_id = classes.id AND students.class_id = classes.id
            WHERE
            schedules.id = "'.$id.'"');
        
        return response()->json($schedule);
    }

    public function filterDay(Request $request)
    {
        $schedules = DB::select('SELECT
            schedules.id,
            schedules.`day`,
            TIME_FORMAT(schedules.time, "%H:%i") as time,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.`name` as teacher,
            classes.`name` as class,
            rooms.id as room_id,
            rooms.name AS room_name
            FROM
            schedules
            INNER JOIN rooms ON schedules.room_id = rooms.id
            INNER JOIN students ON schedules.student_id = students.id
            INNER JOIN teachers ON schedules.teacher_id = teachers.id
            INNER JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "'.$request->get('branch').'"
            AND
            schedules.`day` = "'.$request->get('day').'"
            ORDER BY
            schedules.id desc');

        return response()->json($schedules);
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
        // $checkSchedule = DB::select('SELECT
        //     schedules.id
        //     FROM
        //     schedules
        //     WHERE
        //     schedules.`day` = "'.$request->get('day').'" AND
        //     schedules.student_id = "'.$request->get('student').'"
        //     ');

          $schedule = Schedules::find($id);
          $schedule->day = $request->get('day');
          $schedule->time = $request->get('time');
          $schedule->student_id = $request->get('student');
          $schedule->teacher_id = $request->get('teacher');
          $schedule->class_id = $request->get('class');
          $schedule->room_id = $request->get('room');
        // $schedule->branch_id = $request->get('branch');
          $schedule->save();  

          return "Data berhasil di update";

        // return response()->json([
        //     'message' => 'Duplicate'
        // ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $schedule = Schedules::find($id);
        $schedule->delete();

        return "Data berhasil dihapus";
    }
}
