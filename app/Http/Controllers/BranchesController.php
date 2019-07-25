<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branches;
use Illuminate\Support\Facades\DB;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branches::orderBy('id', 'desc')->get();

            return response()->json([
            'message' => 'success',
            'data' => $branches
        ], 200);
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
        $branch = new Branches;
        $branch->name = $request->get('name');
        $branch->address = $request->get('address');
        $branch->phone_no = $request->get('noPhone');
        $branch->save();

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
        $branch = Branches::find($id);
        return response()->json($branch);
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
        $branch = Branches::find($id);
        $branch->name = $request->get('name');
        $branch->address = $request->get('address');
        $branch->phone_no = $request->get('noPhone');
        $branch->save();

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
        $branch = Branches::find($id);
        $branch->delete();

        return "Data berhasil dihapus";
    }
}
