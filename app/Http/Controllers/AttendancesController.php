<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\Attendances;
use App\Classes;
use App\Schedules;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;

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

        $checkNow = collect(\DB::select('SELECT
            (SELECT COUNT(*) FROM attendances WHERE attendances.date = CURRENT_DATE()) as attendancesCount, 
            (SELECT COUNT(*) FROM schedules WHERE schedules.date = CURRENT_DATE()) as schedulesCount'))->first();

        $differenceCount = $checkNow->schedulesCount - $checkNow->attendancesCount;

        if ($differenceCount != 0)
        {
            $schedules = DB::select('SELECT
                id,
                student_id,
                teacher_id,
                class_id,
                start_at,
                end_at,
                branch_id,
                room_id
                FROM
                schedules
                WHERE
                schedules.date = CURRENT_DATE()
                ORDER BY id DESC
                LIMIT '.$differenceCount);

            foreach ($schedules as $schedule) {
                $attendances = new Attendances;
                $attendances->date = date("Y-m-d");
                $attendances->start_at = $schedule->start_at;
                $attendances->end_at = $schedule->end_at;
                $attendances->room_id = $schedule->room_id;
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
                TIME_FORMAT(attendances.start_at, "%H:%i") start_at,
                TIME_FORMAT(attendances.end_at, "%H:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                attendances.room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON attendances.room_id = rooms.id
                WHERE
                attendances.date = CURRENT_DATE
                GROUP BY
                attendances.start_at,
                attendances.class_id,
                attendances.id,
                attendances.teacher_id
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
            TIME_FORMAT(attendances.start_at, "%H:%i") start_at,
            TIME_FORMAT(attendances.end_at, "%H:%i") end_at,
            students.id AS student_id,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.id AS teacher_id,
            teachers.`name` as teacher_name,
            classes.`name`AS class_name,
            attendances.room_id,
            rooms.name AS room_name
            FROM
            attendances
            INNER JOIN schedules ON attendances.schedule_id = schedules.id
            LEFT JOIN students ON attendances.student_id = students.id
            LEFT JOIN teachers ON attendances.teacher_id = teachers.id
            INNER JOIN classes ON attendances.class_id = classes.id
            INNER JOIN rooms ON attendances.room_id = rooms.id
            WHERE
            attendances.date = CURRENT_DATE
            GROUP BY
            attendances.start_at,
            attendances.class_id,
            attendances.id,
            attendances.teacher_id
            ');

        return response()->json($attendances);
    }

    public function filterDate(Request $request)
    {
        if ($request->get('classId') > 0) //Filter By Class
        {
            $attendances = DB::select('SELECT
                attendances.id AS attendances_id,
                attendances.date,
                attendances.teacher_id,
                attendances.teacher_status,
                attendances.student_id,
                attendances.student_status,
                attendances.class_id,
                TIME_FORMAT(attendances.start_at, "%H:%i") start_at,
                TIME_FORMAT(attendances.end_at, "%H:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                attendances.room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON attendances.room_id = rooms.id
                WHERE
                attendances.date BETWEEN "' . $request->get('date') . ' 00:00:00" AND "' . $request->get('date') . ' 23:59:59"
                AND
                attendances.class_id = "' . $request->get('classId') . '"
                ');
                error_log('coba 1');
        } else {
            $attendances = DB::select('SELECT
                attendances.id AS attendances_id,
                attendances.date,
                attendances.teacher_id,
                attendances.teacher_status,
                attendances.student_id,
                attendances.student_status,
                attendances.class_id,
                TIME_FORMAT(attendances.start_at, "%H:%i") start_at,
                TIME_FORMAT(attendances.end_at, "%H:%i") end_at,
                students.id AS student_id,
                students.first_name,
                students.middle_name,
                students.last_name,
                teachers.id AS teacher_id,
                teachers.`name` as teacher_name,
                classes.`name`AS class_name,
                attendances.room_id,
                rooms.name AS room_name
                FROM
                attendances
                INNER JOIN schedules ON attendances.schedule_id = schedules.id
                LEFT JOIN students ON attendances.student_id = students.id
                LEFT JOIN teachers ON attendances.teacher_id = teachers.id
                INNER JOIN classes ON attendances.class_id = classes.id
                INNER JOIN rooms ON attendances.room_id = rooms.id
                WHERE
                attendances.date BETWEEN "' . $request->get('date') . ' 00:00:00" AND "' . $request->get('date') . ' 23:59:59"
                ');
                error_log('coba 2');
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
        elseif (!empty($request->get('room_id'))) 
        {
            $attendances = Attendances::find($id);
            $attendances->room_id = $request->get('room_id');
            $attendances->save();
        }
        elseif (!empty($request->get('start_at'))) 
        {
            $attendances = Attendances::find($id);
            $attendances->start_at = $request->get('start_at');
            $attendances->save();
        }
        elseif (!empty($request->get('end_at'))) 
        {
            $attendances = Attendances::find($id);
            $attendances->end_at = $request->get('end_at');
            $attendances->save();
        }
        elseif (!empty($request->get('student_status'))) 
        {
            $attendances = Attendances::find($id);
            $attendances->student_status = $request->get('student_status');
            $attendances->save();

            $countAttend = collect(\DB::select('SELECT
            Count(attendances.student_status) AS total_attend,
            users.id AS user_id
            FROM
            attendances
            INNER JOIN students ON attendances.student_id = students.id
            INNER JOIN users ON students.user_id = users.id
            WHERE
            attendances.student_id = "'.$request->get('studentId').'" AND
            attendances.date > students.date + INTERVAL 30 DAY AND
            attendances.student_status = 3
            GROUP BY
            users.id'))->first();

            error_log($countAttend->total_attend);
            if($countAttend->total_attend >= 5) 
            {
                $user = Users::find($countAttend->user_id);
                $user->status = 2;

                $user->save();

                return "User Status Changed to Graduated";
            }
            
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
