<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pricings;
use Illuminate\Support\Facades\DB;

class PricingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pricing = DB::select('SELECT
            classes.name as class_name,
            pricings.id,
            pricings.price,
            pricings.total_meetup,
            pricings.duration,
            pricings.type_by_difficulty,
            pricings.type_by_teacher,
            pricings.type_by_participant
            FROM classes INNER JOIN pricings ON pricings.class_id = classes.id
            ORDER BY
            pricings.id DESC');

            return response()->json([
            'message' => 'success',
            'data' => $pricing
        ], 200);
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
        $pricing = new Pricings;
        $pricing->class_id = $request->get('classId');
        $pricing->price = $request->get('price');
        $pricing->total_meetup = $request->get('meetup');
        $pricing->duration = $request->get('duration');
        $pricing->type_by_difficulty = $request->get('difficulty');
        $pricing->type_by_teacher = $request->get('teacher');
        $pricing->type_by_participant = $request->get('participant');
        $pricing->save();

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
        $pricing = DB::select('SELECT
            classes.id as class_id,
            classes.name as class_name,
            pricings.id,
            pricings.price,
            pricings.total_meetup,
            pricings.duration,
            pricings.type_by_difficulty,
            pricings.type_by_teacher,
            pricings.type_by_participant
            FROM
            classes
            INNER JOIN pricings ON pricings.class_id = classes.id
            WHERE
            pricings.id = "'.$id.'"');
        
        return response()->json($pricing);
    }

    public function getPricingsList()
    {
        $pricing = DB::select('SELECT
            pricings.id,
            classes.`name`,
            pricings.type_by_difficulty,
            pricings.type_by_teacher,
            pricings.type_by_participant
            FROM
            pricings
            INNER JOIN classes ON pricings.class_id = classes.id
            ORDER BY
            classes.`name` ASC');
        
        return response()->json($pricing);
    }

    public function getPrice($id)
    {
        $pricing = DB::select('SELECT
            pricings.price
            FROM
            pricings
            WHERE
            pricings.id = "'.$id.'"');
        
        return response()->json($pricing);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        $pricing = Pricings::find($id);
        $pricing->class_id = $request->get('classId');
        $pricing->price = $request->get('price');
        $pricing->total_meetup = $request->get('meetup');
        $pricing->duration = $request->get('duration');
        $pricing->type_by_difficulty = $request->get('difficulty');
        $pricing->type_by_teacher = $request->get('teacher');
        $pricing->type_by_participant = $request->get('participant');
        $pricing->save();

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
        $pricing = Pricings::find($id);
        $pricing->delete();

        return "Data berhasil dihapus";
    }
}
