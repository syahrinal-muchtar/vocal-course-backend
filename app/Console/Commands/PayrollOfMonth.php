<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Payrolls;
use App\Teachers;
use Illuminate\Support\Facades\DB;

class PayrollOfMonth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payroll:month';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate payroll in one month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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

        $this->info('Payrolls will Generate every one month');
    }
}
