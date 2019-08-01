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
            schedules.id AS schedule_id,
            TIME_FORMAT(schedules.start_at, "%H:%i") AS start_at,
            TIME_FORMAT(schedules.end_at, "%H:%i") AS end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            classes.id AS class_id,
            rooms.id AS room_id,
            rooms.name AS room_name
            FROM
            schedules
            LEFT JOIN rooms ON schedules.room_id = rooms.id
            LEFT JOIN students ON schedules.student_id = students.id
            LEFT JOIN teachers ON schedules.teacher_id = teachers.id
            LEFT JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "'.$request->get('branch').'"
            AND
            schedules.date = CURRENT_DATE');

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
        $schedule = new Schedules;
        $schedule->branch_id = $request->get('branch');
        $schedule->date = $request->get('date');
        $schedule->save();

        return "Data berhasil masuk";
    }

    public function copy(Request $request)
    {
        $schedules = DB::select('SELECT
            schedules.id AS schedule_id,
            schedules.branch_id,
            TIME_FORMAT(schedules.start_at, "%H:%i") AS start_at,
            TIME_FORMAT(schedules.end_at, "%H:%i") AS end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            classes.id AS class_id,
            rooms.id AS room_id,
            rooms.name AS room_name
            FROM
            schedules
            LEFT JOIN rooms ON schedules.room_id = rooms.id
            LEFT JOIN students ON schedules.student_id = students.id
            LEFT JOIN teachers ON schedules.teacher_id = teachers.id
            LEFT JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "' . $request->get('branch') . '"
			AND
            schedules.date = "' . $request->get('dateFrom') . '"');

        $checkSchedule = DB::select('SELECT
            schedules.id
            FROM
            schedules
            WHERE
            schedules.date = "' . $request->get('dateTo') . '"');

        if (empty($checkSchedule)) {
            foreach ($schedules as $schedule) {
                $copySchedules = new Schedules();
                $copySchedules->day = $schedule->day;
                $copySchedules->start_at = $schedule->start_at;
                $copySchedules->end_at = $schedule->end_at;
                $copySchedules->student_id = $schedule->student_id;
                $copySchedules->teacher_id = $schedule->teacher_id;
                $copySchedules->class_id = $schedule->class_id;
                $copySchedules->room_id = $schedule->room_id;
                $copySchedules->branch_id = $schedule->branch_id;
                $copySchedules->date = $request->get('dateTo');
                $copySchedules->save();
            }
        }

        return 'Data Berhasil Masuk';
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

    public function filter(Request $request)
    {
        if($request->get('classId') > 0)
        {
            $schedules = DB::select('SELECT
            schedules.id AS schedule_id,
            TIME_FORMAT(schedules.start_at, "%H:%i") AS start_at,
            TIME_FORMAT(schedules.end_at, "%H:%i") AS end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            classes.id AS class_id,
            rooms.id AS room_id,
            rooms.name AS room_name
            FROM
            schedules
            LEFT JOIN rooms ON schedules.room_id = rooms.id
            LEFT JOIN students ON schedules.student_id = students.id
            LEFT JOIN teachers ON schedules.teacher_id = teachers.id
            LEFT JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "'.$request->get('branch').'"
            AND
            schedules.date = "'.$request->get('date').'"
            AND
            schedules.class_id = "'.$request->get('classId').'"');
        }
        else
        {
            $schedules = DB::select('SELECT
            schedules.id AS schedule_id,
            TIME_FORMAT(schedules.start_at, "%H:%i") AS start_at,
            TIME_FORMAT(schedules.end_at, "%H:%i") AS end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            classes.id AS class_id,
            rooms.id AS room_id,
            rooms.name AS room_name
            FROM
            schedules
            LEFT JOIN rooms ON schedules.room_id = rooms.id
            LEFT JOIN students ON schedules.student_id = students.id
            LEFT JOIN teachers ON schedules.teacher_id = teachers.id
            LEFT JOIN classes ON schedules.class_id = classes.id
            WHERE
            schedules.branch_id = "' . $request->get('branch') . '"
            AND
            schedules.date = "' . $request->get('date') . '"');
        }

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
        $checkSchedule = DB::select('SELECT
            schedules.id
            FROM
            schedules
            WHERE
            schedules.date = "'.$request->get('date').'" AND
            schedules.student_id = "'.$request->get('student').'"
            ');

        if (empty($checkSchedule)) {
            $schedule = Schedules::find($id);
            $schedule->day = $request->get('day') ? $request->get('day') : $schedule->day;
            $schedule->start_at = $request->get('start_at') ? $request->get('start_at') : $schedule->start_at;
            $schedule->end_at = $request->get('end_at') ? $request->get('end_at') : $schedule->end_at;
            $schedule->student_id = $request->get('student_id') ? $request->get('student_id') : $schedule->student_id;
            $schedule->teacher_id = $request->get('teacher_id') ? $request->get('teacher_id') : $schedule->teacher_id;
            $schedule->class_id = $request->get('class_id') ? $request->get('class_id') : $schedule->class_id;
            $schedule->room_id = $request->get('room_id') ? $request->get('room_id') : $schedule->room_id;
            // $schedule->branch_id = $request->get('branch');
            $schedule->save();  

            return "Data berhasil masuk";
        }

        return response()->json([
            'message' => 'Duplicate'
        ], 404);
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
