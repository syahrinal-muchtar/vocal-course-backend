<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transactions;
use Illuminate\Support\Facades\DB;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = DB::select('SELECT
            transactions.id,
            transactions.date,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.`name` AS teacher_name,
            transactions.payment_date,
            transactions.receipt_number,
            transactions.cost,
            transactions.`status`,
            transactions_type.`name` AS transaction_type
            FROM
            transactions
            INNER JOIN students ON transactions.student_id = students.id
            INNER JOIN teachers ON transactions.teacher_id = teachers.id
            INNER JOIN transactions_type ON transactions.transaction_type_id = transactions_type.id
            ORDER BY
            transactions.id DESC
        ');
        
        return response()->json($transactions);
            // WHERE
            // schedules.branch_id = "'.$request->get('branch').'"
    }

    public function filterDate(Request $request)
    {
        $transactions = DB::select('SELECT
            transactions.id,
            transactions.date,
            students.first_name,
            students.middle_name,
            students.last_name,
            teachers.name as teacher_name,
            transactions.payment_date,
            transactions.receipt_number,
            transactions.cost
            FROM
            transactions
            INNER JOIN students ON transactions.student_id = students.id
            INNER JOIN teachers ON transactions.teacher_id = teachers.id
            WHERE
            MONTH(transactions.date) = MONTH("'.$request->get('date').'")
            AND
            YEAR(transactions.date) = YEAR("'.$request->get('date').'")
            ORDER BY
            transactions.id DESC');
        
        return response()->json($transactions);
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
        $transaction = new Transactions;
        $transaction->teacher_id = $request->get('teacher');
        $transaction->student_id = $request->get('student');
        $transaction->date = date("Y-m-d H:i:s");
        $transaction->payment_date = $request->get('payment_date');
        $transaction->receipt_number = $this->generateReceiptNumber();
        $transaction->cost = $request->get('cost');
        $transaction->pricing_id = $request->get('pricing');
        $transaction->transaction_type_id = $request->get('transaction_type');
        $transaction->royalty = $request->get('royalty');
        $transaction->note = $request->get('note');
        $transaction->status = $request->get('status');
        $transaction->save();

        // return "Data berhasil masuk";
    }

    public function generateReceiptNumber()
    {
        $number = mt_rand(100000, 999999); // better than rand()

    // call the same function if the barcode exists already
        if ($this->receiptNumberExist($number)) {
            return $this->generateReceiptNumber();
        }

    // otherwise, it's valid and can be used
        return $number;
    }

    public function receiptNumberExist($number) 
    {
        $receipt = Transactions::where('receipt_number', $number)->first();

        return !empty($receipt);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = DB::select('SELECT
            transactions.id,
            transactions.student_id,
            transactions.teacher_id,
            transactions.date,
            students.first_name,
            students.middle_name,
            students.last_name,
            students.street_address,
            students.email,
            teachers.`name` AS teacher_name,
            transactions.payment_date,
            transactions.receipt_number,
            transactions.royalty,
            transactions.note,
            transactions.cost,
            transactions.pricing_id,
            transactions.status,
            classes.`name` AS class_name,
            pricings.type_by_difficulty,
            pricings.type_by_participant,
            transactions.transaction_type_id,
            transactions_type.`name` AS transaction_type_name
            FROM
            transactions
            INNER JOIN students ON transactions.student_id = students.id
            INNER JOIN teachers ON transactions.teacher_id = teachers.id
            INNER JOIN pricings ON transactions.pricing_id = pricings.id
            INNER JOIN classes ON students.class_id = classes.id
            INNER JOIN transactions_type ON transactions.transaction_type_id = transactions_type.id
            WHERE
            transactions.id = "'.$id.'"');
        
        return response()->json($transaction);
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
        $transaction = Transactions::find($id);
        $transaction->teacher_id = $request->get('teacher');
        $transaction->student_id = $request->get('student');
        $transaction->date = date("Y-m-d H:i:s");
        $transaction->payment_date = $request->get('payment_date');
        $transaction->receipt_number = $request->get('receipt_number');
        $transaction->cost = $request->get('cost');
        $transaction->pricing_id = $request->get('pricing');
        $transaction->transaction_type_id = $request->get('transactionType');
        $transaction->royalty = $request->get('royalty');
        $transaction->note = $request->get('note');
        $transaction->save();

        return "Data berhasil masuk";
    }

    public function updateStatus($id)
    {
        $transaction = Transactions::find($id);
        $transaction->status = 1;
        $transaction->save();

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
        $transaction = Transactions::find($id);
        $transaction->delete();

        return "Data berhasil dihapus";
    }
}
