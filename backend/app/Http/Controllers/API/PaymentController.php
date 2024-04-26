<?php
// 3
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MeshgridConectionResource;
use Illuminate\Http\Request;
use Validator;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use URL;
use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use App\Models\MeshgridConnections;

class PaymentController extends Controller
{
    public function getprojectpaymentbyid($id)
    {
        $pr = Project::where('id',$id)->first();
        if($pr == null)
        {
            return response()->json(['status'=>'error', 'message'=>'project not found', 'data'=>''],400);
        }
        else{
            
            $client =  Client::where('id', $pr->clientid)->first();
            $pr->client = $client;
            $pr->product =  Product::where('id',$pr->productid)->first();
            $data = [
                "amount" => $pr->price,
                "name" => $client->clientname,
                "email" => $client->email,
                "amount_paid" => $pr->payments->sum('amount'),
                "mode_ofPayment" => 	$pr->mode_of_payment,
                "duration_of_payment" => $pr->payment_duration,
            ];

            return response()->json(['status'=>'success', 'message'=>'project fetched successfully', 'data'=>$data],200);
        }
    }//ends function

    public function getMeshgridProjectDetails($id)
    {
        $pr = MeshgridConnections::where(['id'=>$id])->withAll()->first();
        if($pr == null)
        {
            return response()->json(['status'=>'error', 'message'=>'project not found', 'data'=>''],400);
        }
        else{

            return new MeshgridConectionResource($pr); //response()->json(['status'=>'success', 'message'=>'project fetched successfully', 'data'=> $pr],200);
        }
    }

    public function confirmPayment(Request $request,$id,$projectid){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.env("paystac_live_key") 
        ])->get(env("paystac_live_url").$id);
        // payment is made to project total amount
  
        if ($response->object() == null) {
            return response()->json(['status'=>'error', 'message'=>'project not found', 'data'=>$err],400);
        } else {
            // check if amount is equal to project amount 
            $projectModel = Project::where("id",$projectid)->first();
            if($response->object()->data->status === "success"){
                //update project status to paid
                $amountPaid = ($response->object()->data->amount/100);
                $actualAmount = ($response->object()->data->amount/100);
                // get all payment and check if payment has been compleated 
                $paymentModelPaySum = Payment::where("project_id",$projectid)->sum("amount");
                if($amountPaid + $paymentModelPaySum >= $projectModel->price){
                    $projectModel->payment_status = Payment::$available;
                }

                $paymentModel = new Payment();
                $paymentModel->project_id = $projectid;
                $paymentModel->amount = $amountPaid;
                $paymentModel->actual_amount = $actualAmount;
                $paymentModel->order_id = 0;
                $paymentModel->status = Payment::$available;
                $paymentModel->fromwhere = "default";
                $paymentModel->meshgrid_id = 0;
                $projectModel->mode_of_payment = $request->modeofpayment;
                if(empty($paymentModel->type_of_payment)){
                    $paymentModel->type_of_payment = $request->typeofpayment;
                }
                
                if($request->paymentduration > 0){
                    $projectModel->payment_duration = date('d/m/Y', strtotime('+'.$request->paymentduration.' months'));
                }else{
                    $projectModel->payment_duration = 0;
                }
                
                if($projectModel->save() and $paymentModel->save()){
                    return response()->json(['status'=>'success', 'message'=>"Payment was a success", 'data'=>$response->object()],200);
                }else{
                    return response()->json(['status'=>'error', 'message'=>"could not update Project payment status", 'data'=>$projectModel],400);
                }

            }else{
                return response()->json(['status'=>'error', 'message'=>'Could not get a feedback from paystack', 'data'=>$response->object()],400);
            }
            
        }
    }  

    public function addPayment(Request $request,$projectid ){
        // create a payment table migration and log all payments
        $projectModel = Project::where("id",$projectid)->first();
        // create a customer 
        $firstname = "SAO";
        $lastname = str_shuffle("1234567890ghhgABC");
        $phone = "+".str_shuffle("12345678901");
        $email = "SAO_".str_shuffle("1234567890ABC")."@saoenergy.com";
        $data = [
            "first_name" => $firstname,
            "last_name" => $lastname,
            "phone" =>$phone,
            "email" =>$email
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer sk_live_288984bd3b992e67d0836a2fd896cd3667af2f0b' 
        ])->post("https://api.paystack.co/customer",$data);
    
        if($response->object()->status == "success"){
            $customerDetails = $response->object()->data;
            $url = "https://api.paystack.co/dedicated_account";

            $accountPostDetails = [
                "customer" => $customerDetails->id,
                "preferred_bank" => "test-bank",
            ];

            $accountCreation = Http::withHeaders([
                'Authorization' => 'Bearer sk_test_a81674220468d407c42fdd4291f225f590066c02' 
            ])->post($url,$accountPostDetails);

            if($accountCreation->object()->status == "success"){
                // save relevant information to project table .
                $accountNumberDetails = $accountCreation->object()->data;

                $projectModel->mode_of_payment = $request->modeofpayment;
                $projectModel->type_of_payment = $request->typeofpayment;

                // *** note save the account details in the db  for project with the right information
                $projectModel->account_number = $request->typeofpayment;
                $projectModel->account_name = $request->typeofpayment;
                $projectModel->bank = $request->typeofpayment;

                if($request->paymentduration > 0){
                    $projectModel->payment_duration = date('d/m/Y', strtotime('+'.$request->paymentduration.' months'));
                }else{
                    $projectModel->payment_duration = 0;
                }
                
                if($projectModel->save()){
                    // send back account details
                    return response()->json(['status'=>'success', 'message'=>"Account number generated successfully", 'data'=>$accountNumberDetails],200);
                }else{
                    return response()->json(['status'=>'error', 'message'=>"Sorry account number can not be generated at this time,please try again", 'data'=>$accountNumberDetails],400);
                }
                
            }else{
                return response()->json(['status'=>'error', 'message'=>"Payment was a success", 'data'=>$accountCreation->object()],400);
            }

        }
        
    }

    // most of the payment would be backdoor payment foreach of the connections
    // as such we would have to create a new model for payments 
    // add a from where for payment, there are 3 from where now 
    //1 . default 
    //2. meshgrid 
    // 3 . pod connection

    // in their payment relationship on each of the above models add a where clause to indicate the from where
    
    public function confirmPaymentMeshgrid(Request $request,$id,$projectid){
        $response = Http::withHeaders([
            'Authorization' => 'Bearer'.env("paystac_live_key")
        ])->get(env("paystac_live_url").$id);
        // payment is made to project total amount 
  
        if ($response->object() == null) {
            return response()->json(['status'=>'error', 'message'=>'project not found', 'data'=>$err],400);
        } else {
            // check if amount is equal to project amount 
            $projectModel = MeshgridConnections::where("id",$projectid)->first();
            if($response->object()->data->status === "success"){
                //update project status to paid
                $amountPaid = ($response->object()->data->amount/100);
                $actualAmount = ($response->object()->data->amount/100);
                // get all payment and check if payment has been compleated 
                $paymentModelPaySum = Payment::where("meshgrid_id",$projectid)->sum("amount");
                if($amountPaid + $paymentModelPaySum >= $projectModel->amount){
                    $projectModel->payment_status = Payment::$available;
                }

                $paymentModel = new Payment();
                $paymentModel->project_id = $projectModel->project->project->id;
                $paymentModel->amount = $amountPaid;
                $paymentModel->actual_amount = $actualAmount;
                $paymentModel->order_id = 0;
                $paymentModel->fromwhere = "meshgrid";
                $paymentModel->meshgrid_id = $projectid->id;
                $paymentModel->status = Payment::$available;
                $projectModel->mode_of_payment = 0;
                $paymentModel->type_of_payment = $request->typeofpayment;
                $projectModel->payment_duration = 0;
                    
                if($projectModel->save() and $paymentModel->save()){
                    return response()->json(['status'=>'success', 'message'=>"Payment was a success", 'data'=>$response->object()],200);
                }else{
                    return response()->json(['status'=>'error', 'message'=>"could not update Project payment status", 'data'=>$projectModel],400);
                }

            }else{
                return response()->json(['status'=>'error', 'message'=>'Could not get a feedback from paystack', 'data'=>$response->object()],400);
            }
            
        }
    }

}
