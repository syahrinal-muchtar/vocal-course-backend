<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Users;
use App\UsersGroups;
use App\UsersBranches;
use App\Teachers;
use App\Employees;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = Users::orderBy('id', 'asc')->get();

            return response()->json([
            'message' => 'success',
            'data' => $users
        ], 200);
    }

    public function seacrh(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
        $user->username = $request->get('username');
        $user->password = Hash::make($request->get('password'));
        $user->note = $request->get('note');
        $user->joined_at = date("Y-m-d");
        $user->status = 1; //langsung aktif
        $user->save();

        $userGroup = new UsersGroups;
        $userGroup->user_id = $user->id;
        $userGroup->group_id = $request->get('group');
        $userGroup->save();

        if($request->get('group') == 1) //Teacher
        {
            $teacher = new Teachers;
            $teacher->name = $request->get('name');
            $teacher->salary = $request->get('salary');
            $teacher->user_id = $user->id;
            $teacher->save();
        }
        else if($request->get('group') == 2) //Employee
        {
            $employee = new Employees;
            $employee->name = $request->get('name');
            $employee->no_hp = $request->get('noHp');
            $employee->address = $request->get('address');
            $employee->gender = $request->get('gender');
            $employee->birthdate = $request->get('birthdate');
            $employee->user_id = $user->id;
            $employee->save();
        }

        foreach ($request->get('branch') as $branch) {
            $userBranch = new UsersBranches;
            $userBranch->user_id = $user->id;
            $userBranch->branch_id = $branch['value'];
            $userBranch->save();
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
        $user = Users::find($id);
        return response()->json($user);
    }

    public function getBranchs($id)
    {
        $branches = DB::select('SELECT
        branches.`name`,
        branches.id
        FROM
        users_branches
        INNER JOIN branches ON users_branches.branch_id = branches.id
        WHERE
        users_branches.user_id = "'.$id.'"');

        return response()->json($branches);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function filterGroup(Request $request)
    {
        $users = DB::select('SELECT
            users_groups.id,
            users.id,
            users.username,
            users.email,
            users.joined_at,
            groups.id,
            groups.`name`
            FROM
            users_groups
            INNER JOIN users ON users_groups.user_id = users.id
            INNER JOIN groups ON users_groups.group_id = groups.id
            WHERE
            groups.id = "'.$request->group.'"');
        
        return response()->json($users);
    }

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
        $user = Users::find($id);  
        $user->email = $request->get('email');
        $user->username = $request->get('username');
        // $user->password = Hash::make($request->get('password'));

        $user->save();

        return "Data berhasil di Update";
    }

    public function editBalance(Request $request, $id)
    {
        $user = Users::find($id);  
        $user->balance = $request->get('balance');

        $user->save();

        return "Data berhasil di Update";
    }

    public function decBalance($id)
    {
        $student = collect(\DB::select('SELECT
            students.user_id
            FROM
            students
            WHERE
            students.id = "'.$id.'"'))->first();

        $user = Users::find($student->user_id);  
        $user->balance = $user->balance -= 1;

        $user->save();

        return "Data berhasil di Update";
    }

    public function resign($id)
    {
        $user = Users::find($id);
        $user->resigned_at = $user->status == 3 ? $user->resigned_at : date("Y-m-d");
        $user->status = $user->status == 3 ? 1 : 3; // 1 = Active 3 = Resign
        $user->save();

        return "Status karyawan berhasil di update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Users::find($id);
        $user->delete();

        return "Data berhasil dihapus";
    }

    private function getToken($username, $password)
    {
        $token = null;
        //$credentials = $request->only('email', 'password');
        try {
            if (!$token = auth()->attempt( ['username'=>$username, 'password'=>$password])) {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Password or email is invalid',
                    'token'=>$token
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Token creation failed',
            ]);
        }
        return $token;
    }

    public function login(Request $request)
    {
        // $credentials = request(['username', 'password']);

        // if (! $token = auth()->attempt($credentials)) {
        //     return response()->json(['error' => 'Unauthorized'], 401);
        // }

        // return $this->respondWithToken($token);

        $user = Users::where('username', $request->username)->first();

        $branch = collect(\DB::select('SELECT
        branches.id,
        branches.`name`
        FROM
        users_branches
        INNER JOIN branches ON users_branches.branch_id = branches.id
        WHERE
        users_branches.user_id = "'.$user->id.'"'))->first();

        if ($user && \Hash::check($request->password, $user->password)) // The passwords match...
        {
            $token = self::getToken($request->username, $request->password);
            $user->auth_token = $token;
            $user->save();
            $response = ['success'=>true, 'data'=>['id'=>$user->id,'auth_token'=>$user->auth_token,'username'=>$user->username, 'email'=>$user->email, 'branch_id'=>$branch->id, 'branch_name'=>$branch->name, 'role_id'=>$user->role_id]];           
        }
        else 
          $response = ['success'=>false, 'data'=>'Record doesnt exists'];
      
      return response()->json($response, 201);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
