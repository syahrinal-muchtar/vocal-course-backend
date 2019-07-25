<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Payrolls;
use App\Teachers;
use Illuminate\Support\Facades\DB;

class PayrollsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function filterDate(Request $request)
    {
        // $payrolls = DB::select('SELECT
        //     payrolls.id,
        //     payrolls.date,
        //     payrolls.students_count,
        //     payrolls.total_salary,
        //     payrolls.total_vacation,
        //     payrolls.total_absent,
        //     payrolls.total,
        //     teachers.`name`
        //     FROM
        //     payrolls
        //     INNER JOIN teachers ON payrolls.teacher_id = teachers.id
        //     WHERE
        //     payrolls.date BETWEEN "'.$request->get('from_date').' 00:00:00" AND "'.$request->get('to_date').' 23:59:59"');

        $payrolls = DB::select('SELECT
            payrolls.id,
            payrolls.date,
            payrolls.students_count,
            payrolls.total_salary,
            payrolls.total_vacation,
            payrolls.total_absent,
            payrolls.total,
            teachers.`name`
            FROM
            payrolls
            INNER JOIN teachers ON payrolls.teacher_id = teachers.id
            WHERE
            YEAR(payrolls.date)=YEAR("'.$request->get('from_date').'") AND MONTH(payrolls.date) BETWEEN MONTH("'.$request->get('from_date').'") AND MONTH("'.$request->get('to_date').'")
            ORDER BY
            payrolls.id DESC');

        // MONTH(transactions.date) = MONTH("'.$request->get('date').'")
        //     AND
        //     YEAR(transactions.date) = YEAR("'.$request->get('date').'")
        
        return response()->json($payrolls);
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
        $teachers = Teachers::all();

        foreach ($teachers as $teacher) 
        {
            $attendances = DB::select('SELECT
            teacher_attendances.date,
            teacher_attendances.status,
            teachers.id as teacher_id
            FROM
            teacher_attendances
            INNER JOIN teachers ON teacher_attendances.teacher_id = teachers.id
            WHERE
            teachers.id = "'.$teacher->id.'"
            AND
            MONTH(teacher_attendances.date) = MONTH(CURRENT_DATE())');

            $absent = 0;
            $vacation = 0;

            foreach ($attendances as $attendance) 
            {
                if($attendance->status = 1)
                {
                    $absent++;
                }
                elseif($attendance->status = 2)
                {
                    $vacation++;
                }
            }

            $total = ($teacher->salary * $teacher->students_count) - ($absent * 2000);

            $payroll = new Payrolls;
            $payroll->date = date("Y-m-d H:i:s");
            $payroll->teacher_id = $teacher->id;
            $payroll->students_count = $teacher->students_count;
            $payroll->total_salary = $teacher->salary;
            $payroll->total_vacation = $vacation;
            $payroll->total_absent = $absent;        
            $payroll->total = $total;
            $payroll->save();

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
        //
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
