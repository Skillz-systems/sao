<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\Payment;
use DB;

class SiteController extends Controller
{
    //
    public function index(){
        $projectModel = Project::all()->count();
        $clientModel = Client::all()->count();
        $salesModel = Payment::all()->sum("amount");
        $data=[
            "project" => $projectModel,
            "client" => $clientModel,
            "sales" => $salesModel];
        return response()->json(["status"=>"success","message"=>"all statistical data was fetched successfully ","data"=>$data],201);
    }

    public function paymentPerYear(Request $request){
        $year = empty($request->year)?date('Y-m-d'):$request->year;

        $payments = Payment::select(DB::raw('SUM(amount) as total_amount'), DB::raw('MONTH(created_at) as month'))
        ->whereYear('created_at', $year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->get();

        // $payments = Payment::select('id','created_at')
        // ->selectRaw('DATE(created_at) as day')
        // ->groupBy('day', 'id','created_at')
        // ->orderBy('created_at', 'desc')
        // ->get()
        // ->groupBy('day')
        // ->map(function ($payments) {
        //     return [
        //         'date' => $payments->first()->created_at->format('Y-m-d'),
        //         'data' => $payments->toArray(),
        //     ];
        // });
        return response()->json(["status" => "success","message" => "Payment Per Year and months has been","data" => $payments],200);
    }
}
