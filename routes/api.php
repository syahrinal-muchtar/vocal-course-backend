<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => 'jwt.auth'], function () {
  
    // all routes to protected resources are registered here  
    Route::get('users', 'UsersController@index');
});

//Branches Route
$router->get('branches', 'BranchesController@index');

// Users Route
// $router->get('users', 'UsersController@index');
$router->get('user/{id}', 'UsersController@show');
$router->post('user', 'UsersController@store');
$router->patch('user/{id}', 'UsersController@update');
$router->delete('user/{id}', 'UsersController@destroy');
$router->post('user/login', 'UsersController@login');
$router->post('user/logout', 'UsersController@logout');
$router->get('users/filterGroup', 'UsersController@filterGroup');

// Employee Route
$router->get('employees', 'EmployeesController@index');
$router->post('employee', 'EmployeesController@store');
$router->get('employee/{id}', 'EmployeesController@show');
$router->patch('employee/{id}', 'EmployeesController@update');
$router->delete('employee/{id}', 'EmployeesController@destroy');

// Branch Route
$router->get('branches', 'BranchesController@index');
$router->post('branch', 'BranchesController@store');
$router->get('branch/{id}', 'BranchesController@show');
$router->patch('branch/{id}', 'BranchesController@update');
$router->delete('branch/{id}', 'BranchesController@destroy');

// Student Route
$router->get('students', 'StudentsController@index');
$router->get('students/filterYear', 'StudentsController@filterYear');
$router->get('students/filterClass', 'StudentsController@filterClass');
$router->get('students/filterBranch', 'StudentsController@filterBranch');
$router->get('students/groupingClass', 'StudentsController@groupingClass');
$router->get('student/{id}', 'StudentsController@show');
$router->get('student_transaction/{id}', 'StudentsController@historyStudentTransaction');
$router->post('student', 'StudentsController@store');
$router->post('student/upload', 'StudentsController@uploadSignature');
$router->patch('student/{id}', 'StudentsController@update');
$router->patch('student_status/{id}', 'StudentsController@updateStatus');
$router->delete('student/{id}', 'StudentsController@destroy');

// Schedule Route
$router->get('schedules', 'SchedulesController@index');
$router->get('schedules/filter', 'SchedulesController@filter');
$router->get('schedule/{id}', 'SchedulesController@show');
$router->post('schedule', 'SchedulesController@store');
$router->post('schedules/copy', 'SchedulesController@copy');
$router->patch('schedule/{id}', 'SchedulesController@update');
$router->delete('schedule/{id}', 'SchedulesController@destroy');

// Attendances Route
$router->get('attendances', 'AttendancesController@index');
$router->get('attendances/filter', 'AttendancesController@filterDate');
$router->patch('attendance/{id}', 'AttendancesController@update');

// Student Attendances Route
$router->get('student_attendances', 'StudentAttendancesController@index');
$router->get('student_attendance/{id}', 'StudentAttendancesController@show');
$router->get('student_attendances/filterDate', 'StudentAttendancesController@filterDate');
$router->get('student_attendances/filterClass', 'StudentAttendancesController@filterClass');
$router->post('student_attendance', 'StudentAttendancesController@store');
$router->patch('student_attendance/{id}', 'StudentAttendancesController@update');
$router->delete('student_attendance/{id}', 'StudentAttendancesController@destroy');

// Class Route
$router->get('classes', 'ClassesController@index');
$router->get('class/{id}', 'ClassesControllser@show');
$router->post('class', 'ClassesController@store');
$router->patch('class/{id}', 'ClassesController@update');
$router->delete('class/{id}', 'ClassesController@destroy');

// Rooom Route
$router->get('rooms', 'RoomsController@index');
$router->post('room', 'RoomsController@store');
$router->patch('room/{id}', 'RoomsController@update');
$router->delete('room/{id}', 'RoomsController@destroy');

// Roles Route
$router->get('roles', 'RolesController@index');
$router->post('role', 'RolesController@store');
$router->patch('role/{id}', 'RolesController@update');
$router->delete('role/{id}', 'RolesController@destroy');

// Group Route
$router->get('groups', 'GroupsController@index');
// $router->post('role', 'RolesController@store');
// $router->patch('role/{id}', 'RolesController@update');
// $router->delete('role/{id}', 'RolesController@destroy');

// Teacher Route
$router->get('teachers', 'TeachersController@index');
$router->get('teacher/{id}', 'TeachersController@show');
$router->get('teachers/filterStudent', 'TeachersController@filterStudent');
$router->post('teacher', 'TeachersController@store');
$router->patch('teacher/{id}', 'TeachersController@update');
$router->delete('teacher/{id}', 'TeachersController@destroy');

// Teacher Attendances Route
$router->get('teacher_attendances', 'TeacherAttendancesController@index');
$router->get('teacher_attendances/filter', 'TeacherAttendancesController@filterDate');
$router->get('teacher_attendance/{id}', 'TeacherAttendancesController@show');
$router->post('teacher_attendance', 'TeacherAttendancesController@store');
$router->patch('teacher_attendance/{id}', 'TeacherAttendancesController@update');
$router->delete('teacher_attendance/{id}', 'TeacherAttendancesController@destroy');

// Pricing Routes
$router->get('pricings', 'PricingsController@index');
$router->get('pricings/list', 'PricingsController@getPricingsList');
$router->get('pricing/price/{id}', 'PricingsController@getPrice');
$router->get('pricing/{id}', 'PricingsController@show');
$router->post('pricing', 'PricingsController@store');
$router->patch('pricing/{id}', 'PricingsController@update');
$router->delete('pricing/{id}', 'PricingsController@destroy');

// Transaction Route
$router->get('transactions', 'TransactionsController@index');
$router->get('transactions/filter', 'TransactionsController@filterDate');
$router->get('transaction/{id}', 'TransactionsController@show');
$router->patch('transaction_paid/{id}', 'TransactionsController@updateStatus');
$router->post('transaction', 'TransactionsController@store');
$router->patch('transaction/{id}', 'TransactionsController@update');
$router->delete('transaction/{id}', 'TransactionsController@destroy');

// Transaction Type Route
$router->get('transactions_type', 'TransactionsTypeController@index');

// Accounts Route
$router->get('accounts', 'AccountsController@index');


// Payroll Route
$router->get('payrolls', 'PayrollsController@filterDate');
$router->post('payroll', 'PayrollsController@store');