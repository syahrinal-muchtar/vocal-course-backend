<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Students;
use App\Teachers;
use App\Classes;
use App\Users;
use App\UsersGroups;
use Illuminate\Support\Facades\DB;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $classes = Classes::all();
        // $students = array();

        // foreach ($classes as $class) {
        //     $student = array();
        //     $student = DB::select('SELECT
        //         students.id,
        //         students.first_name,
        //         students.middle_name,
        //         students.last_name,
        //         students.age,
        //         students.sex,
        //         students.street_address,
        //         students.cell_phone,
        //         students.home_phone_no,
        //         students.school,
        //         students.email,
        //         classes.`name` as class_name,
        //         students.teacher_id,
        //         teachers.`name` as teacher_name
        //         FROM
        //         students
        //         INNER JOIN classes ON students.class_id = classes.id
        //         INNER JOIN teachers ON students.teacher_id = teachers.id
        //         WHERE
        //         classes.`name` = "'.$class->name.'"
        //         AND
        //         students.status = "'.$request->status.'"
        //         ORDER BY
        //         students.id DESC');

        //     array_push($students, $student != [] ? $student : $class->name);
        // }

        // if (empty($student))
        // {
        //     $student = DB::select('SELECT
        //         students.id,
        //         students.first_name,
        //         students.middle_name,
        //         students.last_name,
        //         students.age,
        //         students.sex,
        //         students.street_address,
        //         students.cell_phone,
        //         students.home_phone_no,
        //         students.school,
        //         students.email,
        //         classes.`name` as class_name
        //         FROM
        //         students
        //         LEFT JOIN classes ON students.class_id = classes.id
        //         WHERE
        //         classes.`name` IS NULL
        //         AND
        //         students.status = "'.$request->status.'"
        //         ORDER BY
        //         students.id DESC');

        //     array_push($students, $student != [] ? $student : 'None');
        // }

            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                YEAR(students.date)= YEAR(Current_date)
                AND
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        
        return response()->json($students); 
    }

    public function groupingClass(Request $request)
    {
        if($request->grouping == 'yes')
        {
            $classes = Classes::all();
            $students = array();

            foreach ($classes as $class) {
                $student = array();
                $student = DB::select('SELECT
                    students.id,
                    students.first_name,
                    students.middle_name,
                    students.last_name,
                    students.age,
                    students.sex,
                    students.street_address,
                    students.cell_phone,
                    students.home_phone_no,
                    students.school,
                    students.email,
                    classes.`name` as class_name,
                    students.teacher_id,
                    teachers.`name` as teacher_name,
                    branches.id AS branch_id,
                    branches.`name` AS branch_name,
users.`status` AS user_status
                    FROM
                    students
                    LEFT JOIN classes ON students.class_id = classes.id
                    LEFT JOIN teachers ON students.teacher_id = teachers.id
                    INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                    WHERE
                    classes.`name` = "'.$class->name.'"
                    AND
                    students.status = "'.$request->status.'"
                    ORDER BY
                    students.id DESC');

                array_push($students, $student != [] ? $student : $class->name);
            }

            if (empty($student))
            {
                $student = DB::select('SELECT
                    students.id,
                    students.first_name,
                    students.middle_name,
                    students.last_name,
                    students.age,
                    students.sex,
                    students.street_address,
                    students.cell_phone,
                    students.home_phone_no,
                    students.school,
                    students.email,
                    classes.`name` as class_name,
                    branches.id AS branch_id,
                    branches.`name` AS branch_name,
users.`status` AS user_status
                    FROM
                    students
                    INNER JOIN branches ON students.branch_id = branches.id
                    LEFT JOIN classes ON students.class_id = classes.id
INNER JOIN users ON students.user_id = users.id
                    WHERE
                    classes.`name` IS NULL
                    AND
                    students.status = "'.$request->status.'"
                    ORDER BY
                    students.id DESC');

                array_push($students, $student != [] ? $student : 'None');
            }
        }
        else
        {
            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                YEAR(students.date)= YEAR(Current_date)
                AND
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        }
        
        return response()->json($students); 
    }

    public function filterBranch(Request $request)
    {
        if($request->branchId > 0)
        {
            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                branches.id = "'.$request->branchId.'"
                AND
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        }
        else
        {
            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        }
         

         return response()->json($students); 
    }

    public function filterClass(Request $request)
    {
        if($request->classId > 0)
        {
            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                classes.id = "'.$request->classId.'"
                AND
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        }
        else
        {
            $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        }
        
        return response()->json($students); 
    }

    public function historyStudentTransaction($id)
    {
        $transaction = DB::select('SELECT
            transactions.payment_date,
            transactions.receipt_number,
            transactions.`status`,
            transactions.cost,
            transactions_type.`name` as transaction_type
            FROM
            transactions
            INNER JOIN transactions_type ON transactions.transaction_type_id = transactions_type.id
            WHERE
            transactions.student_id = "'.$id.'"');
        
        return response()->json($transaction);
    }

    public function filterYear(Request $request)
    {
        // $classes = Classes::all();
        // $students = array();

        // foreach ($classes as $class) {
        //     $student = array();
        //     $student = DB::select('SELECT
        //         students.id,
        //         students.first_name,
        //         students.middle_name,
        //         students.last_name,
        //         students.age,
        //         students.sex,
        //         students.street_address,
        //         students.cell_phone,
        //         students.home_phone_no,
        //         students.school,
        //         students.email,
        //         classes.`name` as class_name
        //         FROM
        //         students
        //         INNER JOIN classes ON students.class_id = classes.id
        //         WHERE
        //         classes.`name` = "'.$class->name.'"
        //         AND
        //         YEAR(students.date)="'.$request->date.'"
        //         ORDER BY
        //         students.id DESC');
        //     array_push($students, $student != [] ? $student : $class->name);
        // }
        $students = DB::select('SELECT
                students.id,
                students.first_name,
                students.middle_name,
                students.last_name,
                students.age,
                students.sex,
                students.street_address,
                students.cell_phone,
                students.home_phone_no,
                students.school,
                students.email,
                classes.`name` as class_name,
                students.teacher_id,
                teachers.`name` as teacher_name,
                branches.id AS branch_id,
                branches.`name` AS branch_name,
users.`status` AS user_status
                FROM
                students
                LEFT JOIN classes ON students.class_id = classes.id
                LEFT JOIN teachers ON students.teacher_id = teachers.id
                INNER JOIN branches ON students.branch_id = branches.id
INNER JOIN users ON students.user_id = users.id
                WHERE
                YEAR(students.date)= "'.$request->date.'"
                AND
                students.status = "'.$request->status.'"
                ORDER BY
                students.id DESC');
        
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
        $user = new Users;
        $user->email = $request->get('email');
        // $user->username = $request->get('username');
        // $user->password = Hash::make($request->get('password'));
        // $user->note = $request->get('note');
        $user->joined_at = date("Y-m-d");
        $user->status = 1; //langsung aktif
        $user->balance = 4;
        $user->save();

        $userGroup = new UsersGroups;
        $userGroup->user_id = $user->id;
        $userGroup->group_id = 5;
        $userGroup->save();

        $student = new Students;
        $student->first_name = $request->get('firstName');
        $student->middle_name = $request->get('middleName') == '' ? '' : $request->get('middleName');
        $student->last_name = $request->get('lastName') == '' ? '' : $request->get('lastName');
        $student->street_address = $request->get('address');
        $student->school = $request->get('school');
        $student->email = $request->get('email');
        $student->birth_date = $request->get('birthDate');
        $student->age = $request->get('age');
        $student->sex = $request->get('sex');
        $student->cell_phone = $request->get('cellPhone');
        $student->home_phone_no = $request->get('homePhone');
        $student->person_responsible_for_bill = $request->get('responsible');
        $student->reason_choose_us = $request->get('reason');
        $student->instructor_audition = $request->get('instructor');
        $student->audition_results = $request->get('result');
        $student->class_id = $request->get('class');
        $student->teacher_id = $request->get('teacher');
        $student->result_days = $request->get('days');
        $student->result_hours = $request->get('hours');
        $student->date = date("Y-m-d H:i:s");
        $student->branch_id = $request->get('branchId');
        $student->status = 1; //Status Bayar
        $student->signature_img_url = $request->get('firstName').$request->get('class').'.png';
        $student->user_id = $user->id;
        $student->save();

        if ($request->get('teacher'))
        {
            $teacher=Teachers::find($request->get('teacher'));
            $teacher->increment('students_count');
            $teacher->save();
        }

        return "Data berhasil masuk";
    }

    public function uploadSignature(Request $request)
    {
        $destination = base_path() . '/public/signature';
        $request->file('signature')->move($destination, $request->picName);

        return "File berhasil di Upload";
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
            students.first_name,
            students.middle_name,
            students.last_name,
            students.birth_date,
            students.age,
            students.sex,
            students.street_address,
            students.cell_phone,
            students.home_phone_no,
            students.school,
            students.email,
            students.person_responsible_for_bill,
            students.reason_choose_us,
            students.instructor_audition,
            students.audition_results,
            students.class_id,
            students.teacher_id,
            students.result_days,
            students.result_hours,
            students.signature_img_url,
            students.date,
            classes.name AS class_name,
            teachers.name AS teacher_name,
            users.`id` AS user_id,
            users.balance
            FROM
            students
            LEFT JOIN classes ON students.class_id = classes.id
            LEFT JOIN teachers ON students.teacher_id = teachers.id
            LEFT JOIN users ON students.user_id = users.id
            WHERE
            students.id = "'.$id.'"');
        
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
        $student = Students::find($id);
        $student->first_name = $request->get('firstName');
        $student->middle_name = $request->get('middleName') == '' ? '' : $request->get('middleName');
        $student->last_name = $request->get('lastName') == '' ? '' : $request->get('lastName');
        $student->street_address = $request->get('address');
        $student->school = $request->get('school');
        $student->email = $request->get('email');
        $student->birth_date = $request->get('birthDate');
        $student->age = $request->get('age');
        $student->sex = $request->get('sex');
        $student->cell_phone = $request->get('cellPhone');
        $student->home_phone_no = $request->get('homePhone');
        $student->person_responsible_for_bill = $request->get('responsible');
        $student->reason_choose_us = $request->get('reason');
        $student->instructor_audition = $request->get('instructor');
        $student->audition_results = $request->get('result');
        $student->class_id = $request->get('class');
        // $student->branch_id = $request->get('branchId');
        $student->teacher_id = $request->get('teacher');
        $student->result_days = $request->get('days');
        $student->result_hours = $request->get('hours');
        if ($request->get('status') == 2)
        {
            $student->status = $request->get('status');
        }
        $student->save();

        return "Data berhasil di Update";
    }

    public function updateStatus(Request $request, $id)
    {
        $student = Students::find($id);
        $student->status = $request->get('status');
        $student->save();

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
        $student = Students::find($id);
        $student->delete();

        return "Data berhasil dihapus";
    }
}
