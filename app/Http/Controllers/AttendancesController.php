<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StudentAttendances;
use App\Attendances;
use App\Classes;
use Illuminate\Support\Facades\DB;

class AttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $checkNow = DB::select('SELECT
        //     attendances.date
        //     FROM
        //     attendances
        //     WHERE
        //     attendances.date = CURRENT_DATE()');

        // if (empty($checkNow))
        // {
        //     $schedules = DB::select('SELECT
        //         id,
        //         student_id,
        //         teacher_id,
        //         class_id
        //         FROM
        //         schedules
        //         WHERE
        //         schedules.`day` = DAYNAME(CURRENT_DATE)');

        //     foreach ($schedules as $schedule) {
        //         $attendances = new Attendances;
        //         $attendances->date = date("Y-m-d");
        //         $attendances->teacher_id = $schedule->teacher_id;
        //         $attendances->teacher_status = 0;
        //         $attendances->student_id = $schedule->student_id;
        //         $attendances->student_status = 0;
        //         $attendances->schedule_id = $schedule->id;
        //         $attendances->class_id = $schedule->class_id;
        //         $attendances->save();
        //     }

        //     $classes = Classes::all();
        //     $attendances = array();

        //     foreach ($classes as $class) {
        //         $attendance = array();
        //         $attendance = DB::select('SELECT
        //             attendances.id AS attendances_id,
        //             attendances.date,
        //             attendances.teacher_id,
        //             attendances.teacher_status,
        //             attendances.student_id,
        //             attendances.student_status,
        //             attendances.class_id,
        //             schedules.start_at,
        //             schedules.`day`,
        //             students.id AS student_id,
        //             students.first_name,
        //             students.middle_name,
        //             students.last_name,
        //             teachers.id AS teacher_id,
        //             teachers.`name` as teacher_name,
        //             classes.`name`AS class_name
        //             FROM
        //             attendances
        //             INNER JOIN schedules ON attendances.schedule_id = schedules.id
        //             LEFT JOIN students ON attendances.student_id = students.id
        //             LEFT JOIN teachers ON attendances.teacher_id = teachers.id
        //             INNER JOIN classes ON attendances.class_id = classes.id
        //             WHERE
        //             attendances.date = CURRENT_DATE
        //             AND
        //             attendances.class_id = "'.$class->id.'"
        //             ');

        //         if(!empty($attendance))
        //         {
        //             array_push($attendances, $attendance);
        //         }
        //     }

        //     return response()->json($attendances);
        // }

        // $classes = Classes::all();
        // $attendances = array();

        // foreach ($classes as $class) {
        //     $attendance = array();
        //     $attendance = DB::select('SELECT
        //         attendances.id AS attendances_id,
        //         attendances.date,
        //         attendances.teacher_id,
        //         attendances.teacher_status,
        //         attendances.student_id,
        //         attendances.student_status,
        //         attendances.class_id,
        //         schedules.start_at,
        //         schedules.`day`,
        //         students.id AS student_id,
        //         students.first_name,
        //         students.middle_name,
        //         students.last_name,
        //         teachers.id AS teacher_id,
        //         teachers.`name` as teacher_name,
        //         classes.`name`AS class_name
        //         FROM
        //         attendances
        //         INNER JOIN schedules ON attendances.schedule_id = schedules.id
        //         LEFT JOIN students ON attendances.student_id = students.id
        //         LEFT JOIN teachers ON attendances.teacher_id = teachers.id
        //         INNER JOIN classes ON attendances.class_id = classes.id
        //         WHERE
        //         attendances.date = CURRENT_DATE
        //         AND
        //         attendances.class_id = "'.$class->id.'"
        //         ');

        //     if(!empty($attendance))
        //     {
        //         array_push($attendances, $attendance);
        //     }
        // }

        $checkNow = DB::select('SELECT
            attendances.date
            FROM
            attendances
            WHERE
            attendances.date = CURRENT_DATE()');

        if (empty($checkNow))
        {
            $schedules = DB::select('SELECT
                id,
                student_id,
                teacher_id,
                class_id
                FROM
                schedules
                WHERE
                schedules.`day` = DAYNAME(CURRENT_DATE)');

            foreach ($schedules as $schedule) {
                $attendances = new Attendances;
                $attendances->date = date("Y-m-d");
                $attendances->teacher_id = $schedule->teacher_id;
                $attendances->teacher_status = 0;
                $attendances->student_id = $schedule->student_id;
                $attendances->student_status = 0;
                $attendances->schedule_id = $schedule->id;
                $attendances->class_id = $schedule->class_id;
                $attendances->save();
            }

            $classes = Classes::all();
            $attendances = array();

            $attendances = DB::select('SELECT
                attendances.id AS attendances_id,
                attendances.date,
                attendances.teacher_id,
                attendances.teacher_status,
                attendances.student_id,
                attendances.student_status,
                attendances.class_id,
                TIME_FORMAT(schedules.start_at, "%h:%i") start_at,
                TIME_FORMAT(schedules.end_at, "%h:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                rooms.id AS room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON schedules.room_id = rooms.id
                WHERE
                attendances.date = CURRENT_DATE
                ');

            return response()->json($attendances);
        }

        $attendances = DB::select('SELECT
            attendances.id AS attendances_id,
            attendances.date,
            attendances.teacher_id,
            attendances.teacher_status,
            attendances.student_id,
            attendances.student_status,
            attendances.class_id,
            TIME_FORMAT(schedules.start_at, "%h:%i") start_at,
            TIME_FORMAT(schedules.end_at, "%h:%i") end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            teachers.`name` as teacher_name,
            classes.`name`AS class_name,
            rooms.id AS room_id,
            rooms.name AS room_name
            FROM
            attendances
            INNER JOIN schedules ON attendances.schedule_id = schedules.id
            LEFT JOIN students ON attendances.student_id = students.id
            LEFT JOIN teachers ON attendances.teacher_id = teachers.id
            INNER JOIN classes ON attendances.class_id = classes.id
            INNER JOIN rooms ON schedules.room_id = rooms.id
            WHERE
            attendances.date = CURRENT_DATE
            ');

        return response()->json($attendances);
    }

    public function filterDate(Request $request)
    {
        $checkNow = DB::select('SELECT
            attendances.date
            FROM
            attendances
            WHERE
            attendances.date = "'.$request->get('date').'"');

        if (empty($checkNow))
        {
            $schedules = DB::select('SELECT
                id,
                student_id,
                teacher_id,
                class_id
                FROM
                schedules
                WHERE
                schedules.`day` = DAYNAME("'.$request->get('date').'")');

            foreach ($schedules as $schedule) {
                $attendances = new Attendances;
                $attendances->date = $request->get('date');
                $attendances->teacher_id = $schedule->teacher_id;
                $attendances->teacher_status = 0;
                $attendances->student_id = $schedule->student_id;
                $attendances->student_status = 0;
                $attendances->schedule_id = $schedule->id;
                $attendances->class_id = $schedule->class_id;
                $attendances->save();
            }

                $attendances = DB::select('SELECT
                    attendances.id AS attendances_id,
                    attendances.date,
                    attendances.teacher_id,
                    attendances.teacher_status,
                    attendances.student_id,
                    attendances.student_status,
                    attendances.class_id,
                    TIME_FORMAT(schedules.start_at, "%h:%i") start_at,
                    TIME_FORMAT(schedules.end_at, "%h:%i") end_at,
                    students.id AS student_id,
                    students.first_name,
                    students.middle_name,
                    students.last_name,
                    teachers.id AS teacher_id,
                    teachers.`name` as teacher_name,
                    classes.`name`AS class_name,
                    rooms.id AS room_id,
                    rooms.name AS room_name
                    FROM
                    attendances
                    INNER JOIN schedules ON attendances.schedule_id = schedules.id
                    LEFT JOIN students ON attendances.student_id = students.id
                    LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                    INNER JOIN classes ON attendances.class_id = classes.id
                    INNER JOIN rooms ON schedules.room_id = rooms.id
                    WHERE
                    attendances.date BETWEEN "'.$request->get('date').' 00:00:00" AND "'.$request->get('date').' 23:59:59"
                    ');

            return response()->json($attendances);
        }

        if($request->get('classId') > 0) //Filter By Class
        {
            $attendances = DB::select('SELECT
                attendances.id AS attendances_id,
                attendances.date,
                attendances.teacher_id,
                attendances.teacher_status,
                attendances.student_id,
                attendances.student_status,
                attendances.class_id,
                TIME_FORMAT(schedules.start_at, "%h:%i") start_at,
                TIME_FORMAT(schedules.end_at, "%h:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                rooms.id AS room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON schedules.room_id = rooms.id
                WHERE
                attendances.date BETWEEN "'.$request->get('date').' 00:00:00" AND "'.$request->get('date').' 23:59:59"
                AND
                attendances.class_id = "'.$request->get('classId').'"
                ');
        }
        else
        {
            $attendances = DB::select('SELECT
                attendances.id AS attendances_id,
                attendances.date,
                attendances.teacher_id,
                attendances.teacher_status,
                attendances.student_id,
                attendances.student_status,
                attendances.class_id,
                TIME_FORMAT(schedules.start_at, "%h:%i") start_at,
                TIME_FORMAT(schedules.end_at, "%h:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                rooms.id AS room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON schedules.room_id = rooms.id
                WHERE
                attendances.date BETWEEN "'.$request->get('date').' 00:00:00" AND "'.$request->get('date').' 23:59:59"
                ');
        }


        return response()->json($attendances);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        if(!empty($request->get('teacher_status')))
        {
            $attendances = Attendances::find($id);
            $attendances->teacher_status = $request->get('teacher_status');
            $attendances->save();
        }
        elseif (!empty($request->get('student_status'))) 
        {
            $attendances = Attendances::find($id);
            $attendances->student_status = $request->get('student_status');
            $attendances->save();
        }
        elseif (!empty($request->get('teacher_id')))
        {
            $attendances = Attendances::find($id);
            $attendances->teacher_id = $request->get('teacher_id');
            $attendances->save();
        }
        elseif (!empty($request->get('student_id')))
        {
            $attendances = Attendances::find($id);
            $attendances->student_id = $request->get('student_id');
            $attendances->save();
        }
        elseif (!empty($request->get('class_id')))
        {
            $attendances = Attendances::find($id);
            $attendances->class_id = $request->get('class_id');
            $attendances->teacher_id = null;
            $attendances->teacher_status = null;
            $attendances->student_id = null;
            $attendances->student_status = null;
            $attendances->save();
        }
        
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
        //
    }
}
