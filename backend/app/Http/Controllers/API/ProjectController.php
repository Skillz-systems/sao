<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as Transactions;

use Validator;
use App\Models\User;
use App\Models\Product;
use App\Models\Miscellaneous;
use Illuminate\Support\Facades\Mail;
use URL;
use Illuminate\Support\Facades\Auth;
use App\Models\State;
use App\Models\Lga;
use App\Models\Client;
use App\Models\Project;
use DB;
use App\Models\Auditrail;
use App\Models\Item;
use App\Models\Subitem;
use App\Models\Stockaddition;
use App\Mail\ProjectPaymentRequest;
use App\Models\ProjectAddress;
use App\Models\ProjectOrder;
use App\Models\ProjectOrderDetails;
use App\Models\ProjectMiscellaneous;
use App\Models\Payment;
use App\Models\ProjectMeshGrid;
use App\Models\MeshgridOrderDetails;
use App\Models\MeshgridAddress;
use App\Models\MeshgridConnections;
use App\Models\MeshgridConnectionAddress;
use App\Models\MeshgridConnectionDetails;




class ProjectController extends Controller
{
    /**
     * This method enables the user to be able to fetch a single meshgrid project by meshgrid id and also fetch all its relationships.
     * @param int $id
     * @return  \Illuminate\Http\JsonResponse
     */
    public function index(){
        return response()->json(["data" => MeshgridConnectionAddress::$available],200);
    }
    public function fetchProjectMeshgrid($id){
        $model = ProjectMeshGrid::where('id',$id)
        ->with("connections","project","order","address","product")
        ->first();
        return response()->json([
            "status"=>"success",
            "message"=>"You have successfully fetched project meshgrid details",
            "data" => $model,
        ],200);
    }

    /**
     * This method enables the user to be able to fetch a single meshgrid project by meshgrid id and also fetch all its relationships.
     * @param int $id
     * @return  \Illuminate\Http\JsonResponse
     */

     public function getMeshgridWithProjectid($id){
        $model = ProjectMeshGrid::where('project_id',$id)
        ->with("connections.client","connections.address","project","order","address","product")
        ->get();
        return response()->json([
            "status"=>"success",
            "message"=>"You have successfully fetched project meshgrid details",
            "data" => $model,
        ],200);
     }

    public function fetchprojects()
    {
        $array = Project::orderby('id','desc')->get();
        $pr = array();
        foreach($array as $one)
        {
               $cl = new Client();
               $client =  $cl::where('id', $one->clientid)->first();
               $one->client = $client;
               if($one->project_selection_mode == "meshgrid"){
                $one->projectnumber =  $one->projectcode."-"."001";
               }else{
                $one->projectnumber =  $one->projectcode."-".$client->clientcode;
               }
               
               $pr[] = $one;
        }
        return response()->json(['status'=>'success', 'message'=>'projects fetched successfully', 'data'=>$pr],200);
    }

    public function getprojectbyid($id)
    {
        $pr = Project::where('id',$id)->with("miscallaneous")->first();

        if(empty($pr)){
            return response()->json(['status'=>'success', 'message'=>'Sorry no available Data'],400);
        }

        if($pr->project_selection_mode == "meshgrid"){
            $pr = Project::where("id",$id)->with("projectmeshgrid","installations","payments")->first();
            return response()->json(['status'=>'success', 'message'=>'project fetched successfully for meshgrid', 'data'=>$pr],200); 
        }

        $installationsModel = $pr->installations;

        $pr->payments;
        foreach($pr->miscallaneous as $key => $value){
            $value->mainmiscallaneous;
        }
        
        foreach($installationsModel as $order){
            $order->orderaddress->state->sname;
            $order->orderaddress->lga->lganame;
            $order->orderdetails;
           
            $data = [
                "inverter" =>[],
                "pannel"=>[],
                "batteries"=>[],
                "accessories" => [],
                "street_light" => [],
                "gas" => [],
                "payment" => [],
            ];
            
            $neworder = $order;
            $neworder->payments;
            // foreach($neworder->payments as $payment){
            //     array_push($data["payment"],$payment);
            // }

            foreach($order->orderdetails as $orderProductDetails){
                
                // if the item is an inverter then push to inverter subarray
                if($orderProductDetails->product[0]['itemid'] == 1 ){
                    array_push($data["inverter"],$orderProductDetails->product[0]);
                    
                }
                // if the item is a solapanel then push to panel subarray
                if($orderProductDetails->product[0]['itemid'] == 2){
                    array_push($data["pannel"],$orderProductDetails->product[0]);
                }
                // if the item is a battery then push to battery subarray
                if($orderProductDetails->product[0]['itemid'] == 3){
                    array_push($data["batteries"],$orderProductDetails->product[0]);
                }

                // if the item is a accessories then push to accessories subarray
                if($orderProductDetails->product[0]['itemid'] == 6){
                    array_push($data["accessories"],$orderProductDetails->product[0]);
                }

                // if the item is a street light then push to street light subarray
                if($orderProductDetails->product[0]['itemid'] == 5){
                    array_push($data["street_light"],$orderProductDetails->product[0]);
                }

                // if the item is a gas then push to gas subarray
                if($orderProductDetails->product[0]['itemid'] == 4){
                    array_push($data["gas"],$orderProductDetails->product[0]);
                }


                
            }
            
            unset($order->orderdetails);
            $order->orderdetails = $data;
            
            
        }
        //$pr->order = $orderDetails;
        
        if($pr == null)
        {
            return response()->json(['status'=>'error', 'message'=>'project not found', 'data'=>''],400);
        }
        else{
            $cl = new Client();
            $client =  $cl::where('id', $pr->clientid)->first();
            $pr->client = $client;
            $pr->product =  Product::where('id',$pr->productid)->first();
            // $pr->instalationitem =  $data;
            
            $pr->product->subitem;
            // add order /each order must fetch address and each other must fets order details / and each order details must fetch product
            return response()->json(['status'=>'success', 'message'=>'project fetched successfully', 'data'=>$pr],200);
        }
    }//ends function

    /**
     * A project can have multiple mesh grid and a mesh grid can have multiple connection ,
     * (linkMeshgridToProject) is an api endpoint that help with adding a new mesh gird to the project .
     * @param \Illuminate\Http\Request  $request
     * @param int $projectid
     * @return  \Illuminate\Http\JsonResponse
     */
    public function linkMeshgridToProject(Request $request,$projectid)
    {
        $model = new ProjectMeshGrid();
        $product = Product::where("id",$request->input("product_id"))->first();
        
        $getInventry = Stockaddition::where([
            "subitemid"=>$product->inverter_type,
            "status" => Stockaddition::$available,
            ])->first();
        if(empty($getInventry)){
            return response()->json(["status"=>"error", "message" => "Sorry we are out if stock for this product"],400);
        }
        $model->project_id = $projectid;
        $model->product_id = $request->input("product_id");
        $model->productname = $request->input("product_name");
        $model->maxmum_connection = $request->input("max_connection");
        $projectModel = Project::where("id",$projectid)->first();

        $addressModel = new MeshgridAddress();// create an address and attach the address id

        $addressModel->projects_id = $projectid;
        $addressModel->client_id = $request->input("clientid");
        $addressModel->log = $request->input("log");
        $addressModel->lat = $request->input("lat");
        $addressModel->address_description = $request->input("full_address");
        $addressModel->states_id = $request->input("state");
        $addressModel->lgas_id = $request->input("lga");
        $addressModel->status = MeshgridAddress::$available;
        if($addressModel->save()){
            $model->deployment_address_id = $addressModel->id;
        }

        $model->pod_installation_price = (int)$getInventry->price/(int)$request->input("max_connection");// this has to do with a fix price for connections (to get the price we have to divide mesh grid price by the maximum connection)
        $model->mesh_grid_price = $getInventry->price;// get this from the product which would link to the inventory for mesh grid

        if($model->save()){

            // allocate inverter and add it to the order 
            $meshgridOrderDetailsModelInverter = new MeshgridOrderDetails();
            $meshgridOrderDetailsModelInverter->meshgrid_project_link_id = $model->id;
            $meshgridOrderDetailsModelInverter->project_id = $projectid;
            $meshgridOrderDetailsModelInverter->product_id = $getInventry->id;
            $meshgridOrderDetailsModelInverter->product_type = "meshgrid";
            $meshgridOrderDetailsModelInverter->client_id = $request->input("clientid");
            $meshgridOrderDetailsModelInverter->status = MeshgridOrderDetails::$available;
            if($meshgridOrderDetailsModelInverter->save()){
                //mark panel as sold.
                $getInventry->status = Stockaddition::$unavailable;
                $getInventry->save();
            }

            // check if product battery id is > 0
            $batteryCost = 0;
            $solarpanelCost = 0;
            if($product->batteries_type > 0){
                //fetch bateries
                $getBatteries = Stockaddition::where([
                    "subitemid"=>$product->batteries_type,
                    "status" => Stockaddition::$available
                    ])->take($product->numberofbatteries)->get();
                // save batteries to the meshgrid order details table 
                foreach($getBatteries as $batterires){
                    $meshgridOrderDetailsModel = new MeshgridOrderDetails();
                    $meshgridOrderDetailsModel->meshgrid_project_link_id = $model->id;
                    $meshgridOrderDetailsModel->product_type = $model->id;
                    $meshgridOrderDetailsModel->product_id = $batterires->id;
                    $meshgridOrderDetailsModel->product_type = "battery";
                    $meshgridOrderDetailsModel->project_id = $projectid;
                    $meshgridOrderDetailsModel->client_id = $request->input("clientid");
                    $meshgridOrderDetailsModel->status = MeshgridOrderDetails::$available;
                    if($meshgridOrderDetailsModel->save()){
                        //mark bettery as sold.
                        $batterires->status = Stockaddition::$unavailable;
                        $batterires->save();

                    }
                    $batteryCost += $batterires->price;

                }
            }
            // update panel details
            if($product->panel_type > 0){
                //fetch panels
                $getPanels = Stockaddition::where([
                    "subitemid"=>$product->panel_type,
                    "status" => Stockaddition::$available
                    ])->take($product->numberofpanels)->get();
                // save panels to the meshgrid order details table 
                foreach($getPanels as $panels){
                    $meshgridOrderDetailsModel = new MeshgridOrderDetails();
                    $meshgridOrderDetailsModel->meshgrid_project_link_id = $model->id;
                    $meshgridOrderDetailsModel->project_id = $projectid;
                    $meshgridOrderDetailsModel->product_id = $panels->id;
                    $meshgridOrderDetailsModel->product_type = "panel";
                    $meshgridOrderDetailsModel->client_id = $request->input("clientid");
                    $meshgridOrderDetailsModel->status = MeshgridOrderDetails::$available;
                    if($meshgridOrderDetailsModel->save()){
                        //mark panel as sold.
                        $panels->status = Stockaddition::$unavailable;
                        $panels->save();

                    }
                    $solarpanelCost += $panels->price;

                }
            }
            // remove meshgrid from the inventory as sold.
            $getInventry->status = Stockaddition::$unavailable;
            // save project price to reflect the cost of mesh grid
            $projectModel->price += $model->mesh_grid_price + $solarpanelCost + $batteryCost;
            $model->pod_installation_price = ($getInventry->price + $solarpanelCost + $batteryCost)/$request->input("max_connection");// this has to do with a fix price for connections (to get the price we have to divide mesh grid price by the maximum connection)
            $model->mesh_grid_price = $getInventry->price + $solarpanelCost + $batteryCost;// get this from the product which would link to the inventory for mesh grid

            if($getInventry->save() && $projectModel->save() && $model->save() ){
                $discountedAmount = (int)$model->pod_installation_price - ((int)$model->pod_installation_price * (int)$projectModel->discount_value/100);// this calculates balance after removing discount
                // $param array is used to hold vital infor which would be used to create the root connection.
                $param = [
                  "meshgrid_id" => $model->id,
                  "order_description" => "root connection" ,
                  "client_id" => $request->input("clientid"),
                  "amount" => $discountedAmount,
                  "actual_amount" => $model->pod_installation_price,
                  "discount" => $projectModel->discount_value == null ? 0:$projectModel->discount_value,
                  "project_id" => $projectModel->id,
                  "stock_id" => $getInventry->id,
                  "address" => [
                    "log" => $request->input("log") ,
                    "lat" => $request->input("lat"),
                    "state_id" => $request->input("state"),
                    "lgas_id" => $request->input("lga"),
                  ]
                  
                ];
                #10 /app/app/Http/Controllers/API/ProjectController.php(335): App\Http\Controllers\API\ProjectController->createRootConnection(Array)
                if($this->createRootConnection($param)){
                    return response()->json(['status'=>'success', 'message'=>'Mesh grid has been added to the project',"data" => $model],200);
                }
                return response()->json(['status'=>'success', 'message'=>'Could not update inventry or project'],400);
            }else{
                return response()->json(['status'=>'success', 'message'=>'Could not update inventry or project'],400);
            }
            
        }else{
            return response()->json(['status'=>'success', 'message'=>'Could not link a meshgrid to a project'],400);
        }
    }

    /**
     * This method would be used an an api endpoint to attach a connection to a meshgrid,
     * connection would include connection fee and pod which are like meters inclusive with a tresh hold which has already been created when the meshgrid was attached to a project.
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\JsonResponse
     */
    
    public function createMeshgridConnections(Request $request){
        // Validate all input as they are all required

        $discountValidator = Validator::make($request->all(),[
            'discount' => 'required',
        ]);
        if($discountValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Discount is required but was not parsed' , 'data'=>''],400);
        }

        $logValidator = Validator::make($request->all(),[
            'log' => 'required',
        ]);
        if($logValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Longitude is required but was not parsed' , 'data'=>''],400);
        }

        $latValidator = Validator::make($request->all(),[
            'lat' => 'required',
        ]);
        if($latValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Latitude is required but was not parsed' , 'data'=>''],400);
        }


        $clientidValidator = Validator::make($request->all(),[
            'client_id' => 'required',
        ]);
        if($clientidValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Client identity is required but was not parsed' , 'data'=>''],400);
        }

        $meshgrididValidator = Validator::make($request->all(),[
            'meshgrid_id' => 'required',
        ]);
        if($meshgrididValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Meshgrid ID is required but was not parsed' , 'data'=>''],400);
        }

        $addressdescriptionValidator = Validator::make($request->all(),[
            'address_description' => 'required',
        ]);
        if($addressdescriptionValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Address description is required but was not parsed' , 'data'=>''],400);
        }

        $stateidValidator = Validator::make($request->all(),[
            'state_id' => 'required',
        ]);
        if($stateidValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'State is required but was not parsed' , 'data'=>''],400);
        }

        $lgasidValidator = Validator::make($request->all(),[
            'lgas_id' => 'required',
        ]);
        if($lgasidValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Local government is required but was not parsed' , 'data'=>''],400);
        }
        
        $orderdescriptionValidator = Validator::make($request->all(),[
            'order_description' => 'required',
        ]);
        if($orderdescriptionValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Order description is required but was not parsed' , 'data'=>''],400);
        }

        $installationpriceValidator = Validator::make($request->all(),[
            'installation_price' => 'required',
        ]);
        if($installationpriceValidator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'Installation price is required but was not parsed' , 'data'=>''],400);
        }
         

        // instanciate all models needed to creat a new connection.
        $getPodItemId = Item::where("item","pod")->first();
        $getPod =  Stockaddition::where([
            "itemid" => $getPodItemId->id,
            "status" => Stockaddition::$available,
        ])->first();
        // confirm if pods are in stock
        if(empty($getPod)){
            return response()->json(['status' => 'error' , 'message'=>'Sorry but PODS are out of stock' , 'data'=>''],400);
        }
        $model = new MeshgridConnections();
        $addressModel = new MeshgridConnectionAddress();
        $connectionDetailModel = new MeshgridConnectionDetails();
        $meshGrid = ProjectMeshGrid::where("id",$request->input("meshgrid_id"))->first();//fetch amount from project meshgrid
        $projectModel = Project::where("id",$meshGrid->project_id)->first();
        if($projectModel->projectmeshgrid->count() >= $meshGrid->maxmum_connection){
            return response()->json(['status' => 'error' , 'message'=>'Please You can not exceed he maximum connection for this meshgrid' , 'data'=>''],400);
        }

        // Create a new address which would be used for the connection.
        $addressModel->log = $request->input("log");
        $addressModel->lat = $request->input("lat");
        $addressModel->client_id = $request->input("client_id");
        $addressModel->meshgrid_id = $request->input("meshgrid_id");
        $addressModel->address_description = $request->input("address_description");
        $addressModel->states_id = $request->input("state_id");
        $addressModel->lgas_id = $request->input("lgas_id");
        $addressModel->status = MeshgridConnectionAddress::$available;
        $addressModel->save();

        // create a new  connection to the meshgrid (Note: connection must include a pod)
        $model->connection_number =$request->input("meshgrid_id").str_shuffle("1234567890ABC") ;
        $model->order_description = $request->input("order_description");
        $model->meshgrid_id = $request->input("meshgrid_id");
        $model->client_id = $request->input("client_id");
        $model->address_id = $addressModel->id;
        if(empty($projectModel->discount_value)){
            $model->amount = ($meshGrid->pod_installation_price + $getPod->price + $request->input("installation_price") );
        }else{
            $model->amount = $meshGrid->pod_installation_price + $getPod->price + $request->input("installation_price") - (($meshGrid->pod_installation_price + $getPod->price + $request->input("installation_price")) * $projectModel->discount_value)/100; //the amount would be a combination of pod price plus the treshold and devided by discount.
        }
        
        $model->actual_amount = ($meshGrid->pod_installation_price + $getPod->price + $request->input("installation_price") );
        $model->discount = $projectModel->discount_value == null ?0:$projectModel->discount_value;
        $model->status = MeshgridConnections::$available;

        //make update to project, pod in the stockadditions and to meshgri_connection_details
        //Note : This code can be refactored using model observers to observe when a creation has been made.
        if($model->save()){
            $getPod->status = Stockaddition::$unavailable;// indicates that pod is nolonger available.
            $getPod->save();
            $projectModel->price += $model->amount; // Update project cost after each connection.
            $projectModel->save();
            $connectionDetailModel->product_type = "pod";
            $connectionDetailModel->product_id = $getPod->id;
            $connectionDetailModel->order_id = $model->id;
            $connectionDetailModel->project_id = $request->input("meshgrid_id");
            $connectionDetailModel->client_id = $request->input("client_id");
            $connectionDetailModel->status = MeshgridConnectionDetails::$available;
            $connectionDetailModel->save();
            // send email to client on how they are to make payment .
            
            return response()->json(["status"=>"success","message"=>"A new connection was done successfully","data"=>$model],201);
        }else{
            return response()->json(["status"=>"error", "message"=>"We could not create a new connection at this time, please try again later."],400);
        }

    }

    /**
     * This method would be used to create a default meshgrid connection.
     * this is the connection we would like to call root connection, where the main meshgrid would be installed.
     * This method has one param which is an array, which structure looks like the below structure.
     * $param = [
     *  "meshgrid_id" => 1,
     *  "order_description" => "root connection" ,
     *  "meshgrid_id" => 1,
     *  "client_id" => 1,
     *  "amount" => 1500,
     *  "actual_amount" => 1300,
     *  "discount" => 10,
     *  "project_id" => 1,
     *  "stock_id" => 1
     *  
     * ]
     * @method Boolean createRootConnection()
     * @param Array $param
     * @return Boolean 
     */
    private function createRootConnection($param){
        // instanciate required models 
        $model = new MeshgridConnections();
        $addressModel = new MeshgridConnectionAddress();
        $connectionDetailModel = new MeshgridConnectionDetails();
        //$meshGrid = ProjectMeshGrid::where("id",$param["meshgrid_id"])->first();//fetch amount from project meshgrid
        $projectModel = Project::where("id",$param["project_id"])->first();

        // Create a new address which would be used for the connection.
        $addressModel->log = $param["address"]["log"];
        $addressModel->lat = $param["address"]["lat"];
        $addressModel->client_id = $param["client_id"];
        $addressModel->meshgrid_id = $param["meshgrid_id"];
        $addressModel->address_description = "Root meshgrid address";
        $addressModel->states_id = $param["address"]["state_id"];
        $addressModel->lgas_id = $param["address"]["lgas_id"];
        $addressModel->status = MeshgridConnectionAddress::$available;
        $addressModel->save();

        $model->address_id = $addressModel->id;
        $model->connection_number =$param["meshgrid_id"].str_shuffle("1234567890ABC") ;
        $model->order_description = $param["order_description"];
        $model->meshgrid_id = $param["meshgrid_id"];
        $model->client_id = $param["client_id"];
        $model->amount = $param["amount"]; //the amount would be a combination of pod price plus the treshold and devided by discount.
        $model->actual_amount = $param["actual_amount"];
        $model->discount = $param["discount"];
        $model->status = MeshgridConnections::$available;

        if($model->save()){
            $projectModel->price = $param["amount"];
            $projectModel->actual_amount = $param["actual_amount"];
            $connectionDetailModel->product_type = "meshgrid";
            $connectionDetailModel->product_id = $param["stock_id"] ;
            $connectionDetailModel->order_id = $model->id;
            $connectionDetailModel->project_id = $param["meshgrid_id"];
            $connectionDetailModel->client_id = $param["client_id"];
            $connectionDetailModel->status = MeshgridConnectionDetails::$available;
            

            if($connectionDetailModel->save() && $projectModel->save()){
                // send an email to the client with details on how to make payment
                return true;
            }else{
                return false;
            }
            return true;
        }else{
            return false;
        }

    }

    public function fetchavailableprojectstatus()
    {
        $arrayofstatus = array("None","Completed", "Ongoing");
        return response()->json(['status'=>'success', 'message'=>'status fetched successfully', 'data'=>$arrayofstatus],200);

    }

    public function fetchavailableprojecttypes()
    {
        $arrayofstatus = array("Residential","Commercial", "Energy storage systems", "Mini grids");
        return response()->json(['status'=>'success', 'message'=>'types fetched successfully', 'data'=>$arrayofstatus],200);

    }

    public function fetchreport(Request $request)
    {
        $alldata = array();

        $proj = DB::table('projects')->where('trashed',0)->distinct()->take(4)->get('productid');
        $topselling = array();
        foreach($proj  as $one)
        {
            $prod = new Product();
            $prod = $prod::where('id', $one->productid)->first();
            $prod->salesCount = Project::where('productid', $prod->id)->count();
            $topselling[] = $prod;
        }

        $totalselling = Project::where('id', '>', 0)->count();
        $alldata["total_intalled_systems"] = $totalselling;
        $alldata["top_selling"] = $topselling;
        $pie_chart_data   = DB::table('projects')->where('stateid','11120')->distinct()->take(4)->get('lgaid');
        $holder = array();
         foreach($pie_chart_data as $one)
         {
               $array = array();
               $array["Label"] = Project::where('lgaid', $one->lgaid)->first()->lga;
               $array["Series"] = Project::where('lgaid', $one->lgaid)->count();
               $holder[] = $array;

         }
         $alldata["pie_chat_data"] = $holder;


         $lastmonths = $this->getpreviousmonths(12);
         $line_graph = array();
         foreach($lastmonths as $on)
         {

            $count = $this->countSalesInMonth($on["monthdate"]);
            $array = array("Label"=>$on["monthdate"], "Series"=>$count);
            $line_graph[] = $array;

         }
         $alldata["line_graph_data"] = $line_graph;

        return response()->json(['status'=>'success', 'message'=>'reports fetched successfully', 'data'=>$alldata],200);

    }

    public function fetchmonthlylinechart()
    {
      $lastmonths = $this->getpreviousmonths(12); // change 12 to current month of that year 
      $line_graph = array();
      foreach($lastmonths as $on)
      {

         $count = $this->countSalesInMonth($on["monthdate"]);
         $array = array("Label"=>$on["monthdate"], "Series"=>$count);
         $line_graph[] = $array;

      }


     return response()->json(['status'=>'success', 'message'=>'reports fetched successfully', 'data'=>$line_graph],200);
    }

    private function countSalesInMonth($monthdate)
    {
      $query = DB::table('projects')
     ->where('created_at', 'like', '%'.$monthdate .'%')
     ->count();
     return $query;
    }


    public function fetchsalesbystateid($id)
    {
        $pie_chart_data   = DB::table('projects')->where('stateid', $id)->distinct()->take(4)->get('lgaid');
        $holder = array();
         foreach($pie_chart_data as $one)
         {
               $array = array();
               $array["Label"] = Project::where('lgaid', $one->lgaid)->first()->lga;
               $array["Series"] = Project::where('lgaid', $one->lgaid)->count();
               $holder[] = $array;

         }
         return response()->json(['status'=>'success', 'message'=>'sales fetched successfully', 'data'=>$holder],200);
    }

    private function getpreviousmonths($number)
    {
      $months = array();
      $currentMonth = date("Y-m");
      //$first = $this->spellingGetter($currentMonth);
      $months[] = array('monthdate'=>$currentMonth);
      for ($i = 1; $i <= $number; $i++)
      {
         $monthspelling = "";
         $monthdate = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
         $months[]  = array('month' => $monthspelling, 'monthdate'=>$monthdate);
      }
      return array_reverse($months);
    }


    public function fetchweeklylinechart()
    {


        $number_of_days = 90;
        $data = $this->computeDifferentWeeklyRangesForAGivenNumberOfDays($number_of_days);

        $fullGraphdata = array();
        foreach ($data as $keym)
         {
          $count = $this->computeAdminAmountBidsWithinDates($keym["newstart"], $keym["newend"]);
          $one = array('Series'=>$count, 'Label'=>$keym["end"]);
          $fullGraphdata[] = $one;
        }

        return response()->json(['status'=>'success', 'message'=>'success', 'data'=>$fullGraphdata],200);
    }


    private function computeDifferentWeeklyRangesForAGivenNumberOfDays($number_of_days)
    {
      $number_of_days = $number_of_days." "."days";
      $date = date_create(date('Y-m-d'));
      date_sub($date, date_interval_create_from_date_string($number_of_days));
      $first = date_format($date, 'Y-m-d');

      $start_date = $first;
      $end_Date = date("Y-m-d");

      $date1 = new \DateTime($start_date);
      $date2 = new \DateTime($end_Date);
      $interval = $date1->diff($date2);
      $myarray = array();
      $weeks = floor(($interval->days) / 7) + 1;

      for($i = 1; $i <= $weeks; $i++){

          $week = $date1->format("W");
          $date1->add(new \DateInterval('P7D'));
          $newEnd=  $date1->format('Y-m-d')." "."23:59:59";
          $newStart = $start_date." "."00:00:00";
          $oneArray = array("start"=>$start_date, "end"=>$date1->format('Y-m-d'), "newend"=>$newEnd, "newstart"=>$newStart);
          $myarray[] = $oneArray;

          $date1->add(new \DateInterval('P1D'));
          $start_date = $date1->format('Y-m-d');
      }
      return $myarray;
    }//end of funtion


    private function computeAdminAmountBidsWithinDates( $start, $end)
    {
      $count = DB::table('projects')
      ->whereBetween('created_at', [$start, $end])
      ->count();
      return $count;
    }

    public function fetchyearlylinechart()
    {


        $data = array("2021", "2022", "2023", "2024", "2025", "2026", "2027", "2028", "2029", "2030", "2031", "2032");
        foreach ($data as $keym)
         {
          $count = $this->computeAdminChartWithinDates($keym);
          $one = array('Series'=>$count, 'Label'=>$keym);
          $fullGraphdata[] = $one;
        }

        return response()->json(['status'=>'success', 'message'=>'success', 'data'=>$fullGraphdata],200);
    }


    private function computeAdminChartWithinDates($year)
    {

      $count = DB::table('projects')
      ->where('created_at', 'like', '%'.$year .'%')
      ->count();
      return $count;
    }

    public function editproject(Request $request)
    {
      $loggedinuser = auth()->guard('sanctum')->user();
      $id = $loggedinuser->id;

      $validator = Validator::make($request->all(),[
          'projectname' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'projectname  is required and projectname must not repeat' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'projecttype' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'projecttype  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'solarsystemsize' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'solarsystemsize  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'numberofbatteries' => 'required|integer|min:0',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'numberofbatteries  is required, must be greater than 0' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'productid' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'productid  is required' , 'data'=>''],400);
      }


      $validator = Validator::make($request->all(),[
          'installationtype' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'installationtype  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'status' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'status  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'clientid' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'clientid  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'lgaid' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'lgaid  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'price' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'Price  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'numberofinverters' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'numberofinverters  is required, must be greater than 0' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'numberofpanels' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'numberofpanels  is required, must be greater than 0' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'batterytypeid' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'batterytypeid  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'invertertypeid' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'invertertypeid  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'solarpaneltypeid' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'solarpaneltypeid  is required' , 'data'=>''],400);
      }

      $validator = Validator::make($request->all(),[
          'id' => 'required|integer|min:1',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'id  is required' , 'data'=>''],400);
      }



      $lga = $lga2 = Lga::where('lgaid',$request->input('lgaid'))->first();

      if($lga == null)
      {
          return response()->json(['status' => 'error' , 'message'=>'Lga is not found' , 'data'=>''],400);
      }


      $cl  =  Client::where('id',$request->clientid)->first();
      if($cl == null)
      {
          return response()->json(['status' => 'error' , 'message'=>'Client is not found' , 'data'=>''],400);
      }

      $oldproject = Project::where('id', $request->input('id'))->first();

      if($oldproject == null)
      {
        return response()->json(['status' => 'error' , 'message'=>'project not found' , 'data'=>''],400);
      }

      $project = Project::where('id', $request->input('id'))->first();

      $batterytype = Subitem::where('id', $request->input('batterytypeid'))->first();
      $oldbatterytype = Subitem::where('id', $request->input('batterytypeid'))->first();
      if($project->batterytypeid != $request->input('batterytypeid'))
      {

        if($request->input('numberofbatteries') > $batterytype->quantity)
        {
          $message = $batterytype->name. " has only". $batterytype->quantity ." left, so you cannot edit";
          return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
        }
      }
      if($project->batterytypeid == $request->input('batterytypeid'))
      {
          $difference = $request->input('numberofbatteries') - $project->numberofbatteries;

          if($difference > $batterytype->quantity)
          {

              $message = $batterytype->name. " has only". $batterytype->quantity ." left, so you cannot edit";
              return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
          }
      }


            $solarpaneltype = Subitem::where('id', $request->input('solarpaneltypeid'))->first();
            $oldsolarpaneltype = Subitem::where('id', $request->input('solarpaneltypeid'))->first();
            if($project->solarpaneltypeid != $request->input('solarpaneltypeid'))
            {

              if($request->input('numberofpanels') > $solarpaneltype->quantity)
              {
                $message = $solarpaneltype->name. " has only". $solarpaneltype->quantity ." left, so you cannot edit";
                return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
              }
            }
            if($project->solarpaneltypeid == $request->input('solarpaneltypeid'))
            {
                $difference = $request->input('numberofpanels') - $project->numberofpanels;

                if($difference > $solarpaneltype->quantity)
                {

                    $message = $solarpaneltype->name. " has only". $solarpaneltype->quantity ." left, so you cannot edit";
                    return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
                }
            }



            $invertertype = Subitem::where('id', $request->input('invertertypeid'))->first();
            $oldinvertertype = Subitem::where('id', $request->input('invertertypeid'))->first();
            if($project->invertertypeid != $request->input('invertertypeid'))
            {

              if($request->input('numberofinverters') > $invertertype->quantity)
              {
                $message = $invertertype->name. " has only". $invertertype->quantity ." left, so you cannot edit";
                return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
              }
            }
            if($project->invertertypeid == $request->input('invertertypeid'))
            {
                $difference = $request->input('numberofinverters') - $project->numberofinverters;

                if($difference > $invertertype->quantity)
                {

                    $message = $invertertype->name. " has only". $invertertype->quantity ." left, so you cannot edit";
                    return response()->json(['status' => 'error' , 'message'=>$message , 'data'=>''],400);
                }
            }



      $lga = $lga->lganame;

      $state = $state2 =  State::where('stateid', $lga2->stateid)->first();
      $state = $state->sname;


      $project->projectname =  $request->input('projectname');
      $project->projecttype =  $request->input('projecttype');
      $project->solarsystemsize =  $request->input('solarsystemsize');
      $project->numberofpanels =  $request->input('numberofpanels');

      $project->numberofbatteries =  $request->input('numberofbatteries');
      $project->description =  $request->input('description');
      $project->productid =  $request->input('productid');
      $project->installationtype =  $request->input('installationtype');


      $project->status =  $request->input('status');
      $project->clientid =  $request->input('clientid');
      $project->lgaid =  $request->input('lgaid');
      $project->price =  $request->input('price');

      $project->lga =  $lga;
      $project->state =  $state;
      $project->stateid =  $state2->stateid;
      $client  =  Client::where('id',$request->clientid)->first();
      $project->clientuserid =  $client->userid;

      $project->numberofinverters =  $request->input('numberofinverters');
      $project->batterytypeid =  $request->input('batterytypeid');
      $project->invertertypeid =  $request->input('invertertypeid');
      $project->solarpaneltypeid =  $request->input('solarpaneltypeid');

      if($project->save())
      {
        if($oldproject->batterytypeid == $request->input('batterytypeid'))
        {
            $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->batterytypeid])->first();
            $st->quantity = $request->input('numberofbatteries');
            $st->save();

            $diff = $request->input('numberofbatteries') - $oldproject->numberofbatteries;


               $subitem = Subitem::where('id', $st->subitemid)->first();
               $subitem->quantity =  $subitem->quantity - $diff;
               $subitem->save();

        }
        if($oldproject->batterytypeid != $request->input('batterytypeid'))
        {
            $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->batterytypeid])->first();
            if($st != null){ $st->delete();}

            $batterytype = Subitem::where('id', $request->input('batterytypeid'))->first();
            $st = new Stockaddition();

            $st->itemid = $batterytype->itemid;
            $st->subitemid = $batterytype->id;
            $st->quantity = $request->input('numberofbatteries');
            $st->userid = $id;
            $st->transactiontype = "sold";
            $st->tracking = substr(str_shuffle("1234567890"),-6).substr(str_shuffle("ABCDFEGHIJKLMNPQRSTUVWZYX"),-2);
            $st->projecttid = $project->id;
            if($st->quantity > 0){
            $st->save();
             }

               $subitemtorestore = Subitem::where('id', $oldproject->batterytypeid)->first();
               $subitemtorestore->quantity =  $subitemtorestore->quantity  + $oldproject->numberofbatteries ;
               $subitemtorestore->save();

               $subitemtoreduce = Subitem::where('id', $request->input('batterytypeid'))->first();
               $subitemtoreduce->quantity = $subitemtoreduce->quantity  - $request->input('numberofbatteries') ;
               $subitemtoreduce->save();
         }




           if($oldproject->invertertypeid == $request->input('invertertypeid'))
           {
               $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->invertertypeid])->first();
               $st->quantity = $request->input('numberofinverters');
               $st->save();

               $diff = $request->input('numberofinverters') - $oldproject->numberofinverters;


                  $subitem = Subitem::where('id', $st->subitemid)->first();
                  $subitem->quantity =  $subitem->quantity - $diff;
                  $subitem->save();

           }
           if($oldproject->invertertypeid != $request->input('invertertypeid'))
           {
               $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->invertertypeid])->first();
               if($st != null){ $st->delete();}

               $invertertype = Subitem::where('id', $request->input('invertertypeid'))->first();
               $st = new Stockaddition();

               $st->itemid = $invertertype->itemid;
               $st->subitemid = $invertertype->id;
               $st->quantity = $request->input('numberofinverters');
               $st->userid = $id;
               $st->transactiontype = "sold";
               $st->tracking = substr(str_shuffle("1234567890"),-6).substr(str_shuffle("ABCDFEGHIJKLMNPQRSTUVWZYX"),-2);
               $st->projecttid = $project->id;
               if($st->quantity > 0){
               $st->save();
                }

                  $subitemtorestore = Subitem::where('id', $oldproject->invertertypeid)->first();
                  $subitemtorestore->quantity =  $subitemtorestore->quantity  + $oldproject->numberofinverters ;
                  $subitemtorestore->save();

                  $subitemtoreduce = Subitem::where('id', $request->input('invertertypeid'))->first();
                  $subitemtoreduce->quantity = $subitemtoreduce->quantity  - $request->input('numberofinverters') ;
                  $subitemtoreduce->save();

            }



              if($oldproject->solarpaneltypeid == $request->input('solarpaneltypeid'))
              {
                  $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->solarpaneltypeid])->first();
                  $st->quantity = $request->input('numberofpanels');
                  $st->save();

                  $diff = $request->input('numberofpanels') - $oldproject->numberofpanels;


                     $subitem = Subitem::where('id', $st->subitemid)->first();
                     $subitem->quantity =  $subitem->quantity - $diff;
                     $subitem->save();

              }
              if($oldproject->solarpaneltypeid != $request->input('solarpaneltypeid'))
              {
                  $st =  Stockaddition::where(['projecttid'=>$oldproject->id, 'subitemid'=>$oldproject->solarpaneltypeid])->first();
                  if($st != null){ $st->delete();}

                  $solarpaneltype = Subitem::where('id', $request->input('solarpaneltypeid'))->first();
                  $st = new Stockaddition();

                  $st->itemid = $solarpaneltype->itemid;
                  $st->subitemid = $solarpaneltype->id;
                  $st->quantity = $request->input('numberofpanels');
                  $st->userid = $id;
                  $st->transactiontype = "sold";
                  $st->tracking = substr(str_shuffle("1234567890"),-6).substr(str_shuffle("ABCDFEGHIJKLMNPQRSTUVWZYX"),-2);
                  $st->projecttid = $project->id;
                  if($st->quantity > 0){
                  $st->save();
                   }

                     $subitemtorestore = Subitem::where('id', $oldproject->solarpaneltypeid)->first();
                     $subitemtorestore->quantity =  $subitemtorestore->quantity  + $oldproject->numberofpanels ;
                     $subitemtorestore->save();

                     $subitemtoreduce = Subitem::where('id', $request->input('solarpaneltypeid'))->first();
                     $subitemtoreduce->quantity = $subitemtoreduce->quantity  - $request->input('numberofpanels') ;
                     $subitemtoreduce->save();

               }

          $statement = "Edited Project  with name ". $project->projectname;
          $changes =  json_encode($project->getChanges());
          $this->logAudit($loggedinuser->email, $statement, $request->ip(), $request->server('HTTP_USER_AGENT'), $changes);

          return response()->json(['status'=>'success', 'message'=>'project edited successfully', 'data'=>$project],200);
      }
      else{
          return response()->json(['status'=>'error', 'message'=>'could not edit project', 'data'=>''],400);
      }

    }


    public function deleteproject(Request $request)
    {
      $loggedinuser = auth()->guard('sanctum')->user();
      $id = $loggedinuser->id;
      if($loggedinuser->role != 1 && $loggedinuser->role != 2)
      {
        return response()->json(['status'=>'error', 'message'=>'you dont have write and edit access',  'data' =>''],400);
      }


      $validator = Validator::make($request->all(),[
          'id' => 'required',
      ]);
      if($validator->fails()){
      return response()->json(['status' => 'error' , 'message'=>'id  is required ' , 'data'=>''],400);
      }

      $id =  $request->input('id');
      $project = new Project();
      $project = $project::where('id',$id)->first();

      if($project == null)
      {
        return response()->json(['status'=>'error', 'message'=>'project not found',  'data' =>''],400);
      }

      $project->trashed = 1;
      $project->save();

      $statement = "Deleted Project  with name ". $project->projectname;
      $this->logAudit($loggedinuser->email, $statement, $request->ip(), $request->server('HTTP_USER_AGENT'), $project);
      return response()->json(['status'=>'success', 'message'=>'project deleted successfully', 'data'=>''],200);

    }

    private function logAudit($email, $action, $ip, $useragent, $object="null")
    {
      $datetime = new \DateTime("Africa/Lagos");
      $auditlog = new Auditrail();
      $auditlog->email = $email;
      $auditlog->action = $action;

      $auditlog->time =  $datetime->format("Y-m-d H:i:s");
      $auditlog->ip =  $ip;
      $auditlog->useragent = $useragent;
      $auditlog->object =  $object;
      $auditlog->save();
    }
    
    /**
     * this method would be used to create projects without any product or inventory
     *  @param \Illuminate\Http\Request  $request
     *  @return  \Illuminate\Http\JsonResponse
     */
    public function createProject(Request $request)
    {
        $loggedinuser = auth()->guard('sanctum')->user();

        $projectNameValidator = Validator::make($request->all(),[
            'projectname' => 'required',
        ]);

        $descriptionValidator = Validator::make($request->all(),[
            'description' => "required",
            
        ]);

        if($descriptionValidator->fails() || $projectNameValidator->fails()){
            return response()->json(["status" => "error", "message" => "Sorry descripton and project name is required"],400);
        }

       

        
        /**
         * check if the product mode is with product and not stocks
         */
        if($request->input("project_selection_mode") == "product"){
            return $this->createProjectWithProduct($request);
        }

         /**
         * check if the product mode is by stock single sales
         */
        if($request->input("project_selection_mode") == "single"){
            return $this->createProjectWithStocks($request);
        }

        /**
         * check if the product mode  is a meshgrid
         */
        if($request->input("project_selection_mode") == "meshgrid"){
            return $this->createProjectWithMeshgrid($request);
        }

        return response()->json(["status" =>'error','message'=>"good","data" => $request ],201);

    }

     /**
     * this method would be used to create projects using products
     *  @param \Illuminate\Http\Request  $request
     *  @return  \Illuminate\Http\JsonResponse
     */

    public function createProjectWithProduct($request)
    {
        //check that the unique option which would determin if product id is available
        // if(!empty($request->input('productid'))){

        // }

        $priceValidator = Validator::make($request->all(),[
            "price" => "required",
        ]);

        $clientidValidator = Validator::make($request->all(),[
            "clientid" => "required",
        ]);

        if($priceValidator->fails() or $clientidValidator->fails()){
            return response()->json(['status'=>'error', 'message'=>'Please price and client info is required', 'data'=>"Please price and client info is required"],400);
        }


        $getProductCheck = Product::where("id",$request->input('productid'))->first();
        $subItemModelInverter = Stockaddition::where(
            [
                ['subitemid', '=', $getProductCheck->inverter_type],
                ['status', '=',Stockaddition::$available ],
            ]
        )->get();
        // create order details for inverter and save 
    
        if(count($subItemModelInverter) < $getProductCheck->numberofinverters * count(json_decode($request->address))){
            // please you dont have enough inverters to complete this transaction
            return response()->json(['status'=>'error', 'message'=>'please you dont have enough inverters to complete this transaction', 'data'=>"please you dont have enough inverters to complete this transaction"],400);
        }

        $subItemModelInverter = Stockaddition::where(
            [
                ['subitemid', '=', $getProductCheck->panel_type],
                ['status', '=', Stockaddition::$available],
            ]
        )->get();
        if(count($subItemModelInverter) < $getProductCheck->numberofpanels * count(json_decode($request->address))){
            // please you dont have enough panel to complete this transaction
            return response()->json(['status'=>'error', 'message'=>'please you dont have enough panel to complete this transaction', 'data'=>"please you dont have enough panel to complete this transaction"],400);
            
        }

        $subItemModelInverter = Stockaddition::where(
            [
                ['subitemid', '=', $getProductCheck->batteries_type],
                ['status', '=', Stockaddition::$available],
            ]
        )->get();

        if(count($subItemModelInverter) < $getProductCheck->numberofbatteries * count(json_decode($request->address))){
            // please you dont have enough batteries to complete this transaction
            return response()->json(['status'=>'error', 'message'=>'please you dont have enough batteries to complete this transaction', 'data'=>"please you dont have enough batteries to complete this transaction"],400);
            
        }

        foreach($getProductCheck->accessories as $accessoryValue){
            //get product with id
            $subItemModelAccessories = Stockaddition::where(
                [
                    ['subitemid', '=', $accessoryValue->subitem_id],
                    ['status', '=', Stockaddition::$available],
                ])->get();
            $subitem = Subitem::where("id",$accessoryValue->subitem_id)->first();
            if($accessoryValue->quantity * count(json_decode($request->address)) >  count($subItemModelAccessories)){
                return response()->json(['status'=>'error', 'message'=>"Please create ". ($accessoryValue->quantity * count(json_decode($request->address)) - count($subItemModelAccessories)). " more Accessories with the name ". $subitem->name, 'data'=>""],400);
            }
        }

        


        $loggedinuser = auth()->guard('sanctum')->user();
        $id = $loggedinuser->id;
        

        // Validate Request 

        // $validator = Validator::make($request->all(),[
        //     'projectname' => 'required|unique:projects',
        // ]);
        // if($validator->fails()){
        // return response()->json(['status' => 'error' , 'message'=>'projectname  is required and projectname must not repeat' , 'data'=>''],400);
        // }

        // Models Init
        $inventoryModel = new Stockaddition();
        $productModel = new Product();
        $projectModel = new Project();
        $clientModel = Client::where('id',$request->input('clientid'))->first() ;

        //save project


        $projectModel->projectname =  $request->input('projectname');
        $projectModel->projecttype =  $request->input('projecttype');
        $projectModel->solarsystemsize =  $request->input('solarsystemsize');
        $projectModel->numberofpanels =  $request->input('numberofpanels');

        $projectModel->numberofbatteries =  $request->input('numberofbatteries');
        $projectModel->description =  $request->input('description');
        $projectModel->productid =  $request->input('productid');
        $projectModel->installationtype =  $request->input('installationtype');


        $projectModel->status =  $request->input('status');
        $projectModel->clientid =  $request->input('clientid');
        $projectModel->lgaid =  $request->input('lgaid');
        $projectModel->price =  $request->input('price');
        $projectModel->stateid =  $request->input('stateid');
        $projectModel->projectcode =  str_shuffle("1234567890ABC");
        $projectModel->addedby =  $id;
        $projectModel->project_selection_mode = $request->input("project_selection_mode");
        
        // note check availability of stuck before entring project
        Transactions::beginTransaction();
        
        //try {

            if($projectModel->save()){
                // after project have been created we then create all instalation address associated with the project 
                // create a loop of address instance to create all addres
                // address created would be looped to create order
                $getProduct = Product::where("id",$projectModel->productid)->first();
                foreach(json_decode($request->address) as $key => $value){
                    $projectAddressModel = new ProjectAddress() ;
                    $projectAddressModel->projects_id = $projectModel->id;
                    if(isSet($value->log) && !empty($value->log)){
                        $projectAddressModel->log = $value->log;
                        $projectAddressModel->lat = $value->lat;
                    }
                    
                    $projectAddressModel->client_id = $projectModel->clientid;
                    $projectAddressModel->address_description = $value->address;
                    $projectAddressModel->states_id = $value->states_id;
                    $projectAddressModel->lgas_id = $value->lgas_id;
                    $projectAddressModel->status= ProjectAddress::$available;
                    $projectAddressModel->save();
                }
    
                
                $getAddress = ProjectAddress::where("projects_id",$projectModel->id)->get();
                // using the address as a point to make product selection for the project and create an order and an order details
                $projectAmout = 0;
                foreach($getAddress as $addressKey => $addressValues){
                    //create a new order and link order to 
                    $orderAmount = 0;
                    $projectOrderModel = new ProjectOrder();
                    $projectOrderModel->order_number = $projectModel->id."_".str_shuffle("1234567890ABC");
                    $projectOrderModel->order_description = $projectModel->description;
                    $projectOrderModel->project_id = $projectModel->id;
                    $projectOrderModel->client_id = $projectModel->clientid;
                    $projectOrderModel->status = ProjectOrder::$available;
                    $projectOrderModel->address_id = $addressValues->id;
                    if($projectOrderModel->save()){
                        
                        if($getProduct->numberofinverters > 0){ 
                            for($i = 0; $i < $getProduct->numberofinverters; $i++){
                                $projectOrderDetailsModel = new ProjectOrderDetails() ;
                                //get product with id
                                
                                $subItemModelInverter = Stockaddition::where(
                                        [
                                            ['subitemid', '=', $getProduct->inverter_type],
                                            ['status', '=', Stockaddition::$available],
                                        ]
                                    )->first();
                                    // create order details for inverter and save 
                                $orderAmount += $subItemModelInverter->price;
                                $orderDetails = new ProjectOrderDetails();
                                $orderDetails->product_type = 0;
                                $orderDetails->product_id = $subItemModelInverter->id;
                                $orderDetails->order_id = $projectOrderModel->id;
                                $orderDetails->project_id = $projectModel->id;
                                $orderDetails->client_id = $projectModel->clientid;
                                $orderDetails->status = ProjectOrderDetails::$available;
                                
                                if($orderDetails->save()){
                                    $subItemModelInverter->status = Stockaddition::$unavailable;
                                    $subItemModelInverter->save();
                                }
                            }
    
                        }
                        
    
                        if($getProduct->numberofpanels > 0){
                            for($i = 0; $i < $getProduct->numberofpanels; $i++){
                                $projectOrderDetailsModel = new ProjectOrderDetails() ;
                                //get product with id
                        
                                $subItemModelInverter = Stockaddition::where(
                                        [
                                            ['subitemid', '=', $getProduct->panel_type],
                                            ['status', '=', Stockaddition::$available],
                                        ]
                                    )->first();
                                $orderAmount += $subItemModelInverter->price;
                                // create order details for inverter and save 
                                $orderDetails = new ProjectOrderDetails();
                                $orderDetails->product_type = 0;
                                $orderDetails->product_id = $subItemModelInverter->id;
                                $orderDetails->order_id = $projectOrderModel->id;
                                $orderDetails->project_id = $projectModel->id;
                                $orderDetails->client_id = $projectModel->clientid;
                                $orderDetails->status = ProjectOrderDetails::$available;
                        
                                if($orderDetails->save()){
                                    $subItemModelInverter->status = Stockaddition::$unavailable;
                                    $subItemModelInverter->save();
                                }
    
                            }
                        }
    
                        if($getProduct->numberofbatteries > 0){
                            for($i = 0; $i < $getProduct->numberofbatteries; $i++){
                                $projectOrderDetailsModel = new ProjectOrderDetails() ;
                                //get product with id
                        
                                $subItemModelInverter = Stockaddition::where(
                                        [
                                            ['subitemid', '=', $getProduct->batteries_type],
                                            ['status', '=', Stockaddition::$available],
                                        ]
                                    )->first();
                                $orderAmount += $subItemModelInverter->price;
                                // create order details for inverter and save 
                                $orderDetails = new ProjectOrderDetails();
                                $orderDetails->product_type = 0;
                                $orderDetails->product_id = $subItemModelInverter->id;
                                $orderDetails->order_id = $projectOrderModel->id;
                                $orderDetails->project_id = $projectModel->id;
                                $orderDetails->client_id = $projectModel->clientid;
                                $orderDetails->status = ProjectOrderDetails::$available;
                        
                                if($orderDetails->save()){
                                    $subItemModelInverter->status = Stockaddition::$unavailable;
                                    $subItemModelInverter->save();
                                }
    
                            }
                        }
                        //return response()->json(['status'=>'success', 'message'=>'project saved successfully', 'data'=>$getProduct->accessories],200);
                        if(!empty($getProduct->accessories)){
                            foreach($getProduct->accessories as $accessoryValue){
                                for($i = 0 ; $i < $accessoryValue->quantity; $i++){
                                    $projectOrderDetailsModel = new ProjectOrderDetails() ;
                                    //get product with id
                            
                                    $subItemModelAccessories = Stockaddition::where(
                                            [
                                                ['subitemid', '=', $accessoryValue->subitem_id],
                                                ['status', '=', Stockaddition::$available],
                                            ]
                                        )->first();
                
                                    $orderAmount += $subItemModelAccessories->price;
                                    // create order details for subItemModelAccessories and save 
                                    $orderDetails = new ProjectOrderDetails();
                                    $orderDetails->product_type = 0;
                                    $orderDetails->product_id = $subItemModelAccessories->id;
                                    $orderDetails->order_id = $projectOrderModel->id;
                                    $orderDetails->project_id = $projectModel->id;
                                    $orderDetails->client_id = $projectModel->clientid;
                                    $orderDetails->status = ProjectOrderDetails::$available;
                            
                                    if($orderDetails->save()){
                                        $subItemModelAccessories->status = Stockaddition::$unavailable;
                                        $subItemModelAccessories->save();
                                    }
            
                                    
                                }
                            }
    
                        }
    
                        if(!empty($getProduct->numberoflight) and $getProduct->numberoflight > 0){
                            
                            for($i = 0 ; $i < $getProduct->numberoflight; $i++){
                                $projectOrderDetailsModel = new ProjectOrderDetails() ;
                                //get product with id
                        
                                $subItemModelLight = Stockaddition::where(
                                        [
                                            ['subitemid', '=', $getProduct->light_type],
                                            ['status', '=', Stockaddition::$available],
                                        ]
                                    )->first();
                                $orderAmount += $subItemModelLight->price;
                                // create order details for inverter and save 
                                $orderDetails = new ProjectOrderDetails();
                                $orderDetails->product_type = 0;
                                $orderDetails->product_id = $subItemModelLight->id;
                                $orderDetails->order_id = $projectOrderModel->id;
                                $orderDetails->project_id = $projectModel->id;
                                $orderDetails->client_id = $projectModel->clientid;
                                $orderDetails->status = ProjectOrderDetails::$available;
                        
                                if($orderDetails->save()){
                                    $subItemModelLight->status = Stockaddition::$unavailable;
                                    $subItemModelLight->save();
                                }
        
                                
                            }
                        
    
                        }
                        if(!empty($request->input('miscellaneous'))){
                            foreach(json_decode($request->input('miscellaneous')) as $key => $value ){
                                $model = new ProjectMiscellaneous();
                                $model->project_id = $projectModel->id;
                                $model->order_id = $projectOrderModel->id;
                                $model->miscellaneous_id = $value->miscellaneous_id;
                                $model->amount = $value->amount/count($getAddress);
                                $orderAmount += $value->amount/count($getAddress);
                                $model->status = ProjectMiscellaneous::$available;
                                $model->save();
                            }
                        }
                        // add and accessories to project , implement discount and also add vat to the implementation
                    }

                    $projectAmout +=  $orderAmount;
                    //$actualOrderAmount = $orderAmount;
                    $amountAfterDiscount = (int)$orderAmount - ((int)$orderAmount * (int)$request->discount / 100);
                    $amountAfterVat = (int)$amountAfterDiscount + ((int)$amountAfterDiscount * 7.5 / 100);
                    $projectOrderModel->amount = $amountAfterVat;
                    $projectOrderModel->actual_amount = $orderAmount;
                    $projectOrderModel->save();
    
                }

                $projectModel->actual_amount = (int)$projectAmout;
                $amountAfterDiscountProject = (int)$projectAmout - ((int)$projectAmout * (int)$request->discount / 100);
                $amountAfterVatProject = (int)$amountAfterDiscountProject + ((int)$amountAfterDiscountProject * 7.5 / 100);
                $projectModel->discount_value = (int)$request->discount;
                $projectModel->price = (int)$amountAfterVatProject;
                if($projectModel->save()){
                    //send email to client now 
                    Transactions::commit();
                    try{
    
                        Mail::to($clientModel->email)->send(New ProjectPaymentRequest(["link" => $projectModel->id,"firstname" => $clientModel->clientname,"amount" => $projectModel->price]));
                        
                        return response()->json(['status'=>'success', 'message'=>'project saved successfully', 'data'=>$projectModel],200);
                   }
                   catch(\Exception $e){
                    return response()->json(['status'=>'success', 'message'=>'project saved successfully', 'data'=>$projectModel],200);
                   }
                    
                }

                return response()->json(['status'=>'error', 'message'=>'Something went wrong', 'data'=>$projectModel],400);
    
                
                
    
            }else{
                return response()->json(['status'=>'error', 'message'=>'We could not create a project at this time, please try again later (Project not saved)', 'data'=>"please you dont have enough batteries to complete this transaction"],400);
            }
        // } catch (\Exception $e) {
        //     Transactions::rollback();
        //     return response()->json(['status'=>'error', 'message'=>'We could not create a project at this time, please try again later (Stop Transaction)', 'data'=>$e],400);
        // }
       


    }
    
    /**
     * this method would be used to create projects using single products in stocks
     *  @param \Illuminate\Http\Request  $request
     *  @return  \Illuminate\Http\JsonResponse
     */
    public function createProjectWithStocks($request)
    {
        return response()->json(['status'=>'error', 'message'=>"Single product mode notyet available",  ],400);
    }

     /**
     * this method would be used to create projects using mesh grid product 
     *  @param \Illuminate\Http\Request  $request
     *  @return  \Illuminate\Http\JsonResponse
     */
    public function createProjectWithMeshgrid($request)
    {
        $projectModel = new Project();
        $projectModel->projectname = $request->input("projectname");
        $projectModel->projecttype = $request->input("projecttype");
        $projectModel->installationtype = $request->input("installationtype");
        $projectModel->status = Project::$available;
        $projectModel->project_selection_mode = $request->input("project_selection_mode");
        $projectModel->description = $request->input("description");
        if($projectModel->save()){
            return response()->json(['status'=>'success', 'message'=>"Mesh Grid Project was created successfuly", 'data'=>$projectModel ],201);
        }else{
            return response()->json(['status'=>'error', 'message'=>"Mesh Grid Project was could not be created",  ],400);
        }
        

    }

    /**
     * this method would be used to update address longitude and latitude 
     *  @param \Illuminate\Http\Request  $request
     *  @param integer  $id
     *  @return  \Illuminate\Http\JsonResponse
     */
    public function updateGeoAddress(Request $request, $id)
    {
        $model = ProjectAddress::where("id",$id)->first();
        // effect the update of the latitude and longitude
        $model->log = $request->input("log");
        $model->lat = $request->input("lat");
        if($model->save()){
            return response()->json(['status'=>'success', 'message'=>'Longitude and Latitude have been updated successfully. ', 'data'=>$model],200);
        }
        return response()->json(['status'=>'error', 'message'=>'We could not update the Longitude and Latitude', 'data'=>$projectModel],400);
    }
    
    /**
     * This route would use the project id and enter a payment for the project, this is a project payment backdoor.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function backdoorPayment(Request $request, $id)
    {
        // request keys in json sample
        // {
        //     "amount":"233",
        //     "actual_amount":"233",
        //     "typeofpayment":"233",
        //     "modeofpayment":"233",
        //     "paymentduration":"233",

        // }
        // check to make sure user is loggedin
        $loggedinuser = auth()->guard('sanctum')->user();
        if(empty($loggedinuser)){
            return response()->json(['status'=>'error', 'message'=>'No user was logged in '],400);
        }
        if((int)$loggedinuser->role !== 1){
            return response()->json(['status'=>'error', 'message'=>'User does not have priv to call this route '],400);
        }

        // check to make sure project exist
        $project = Project::where('id',$id)->first();
        if(empty($project)){
            return response()->json(['status'=>'error', 'message'=>'project does not exist'],400);
        }

        $validator = Validator::make($request->all(),[
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'all fields are required' , 'data'=>''],400);
        }

        // check all request data was validated

        // proccess payment
        $amountPaid = $request->input("amount");
        $actualAmount = $request->input("actual_amount");

        // get all payment and check if payment has been compleated 
        $paymentModelPaySum = Payment::where("project_id",$id)->sum("amount");
        if($amountPaid + $paymentModelPaySum >= $project->price){
            $project->payment_status = Project::$available;
        }

        $paymentModel = new Payment();
        $paymentModel->project_id = $id;
        $paymentModel->amount = $amountPaid;
        $paymentModel->actual_amount = $actualAmount;
        $paymentModel->order_id = 0;
        $paymentModel->status = Payment::$available;
        $paymentModel->type_of_payment = $request->typeofpayment;
        
            
        if($paymentModel->save()){
            if(empty($project->mode_of_payment) or empty($project->payment_duration) ){
                $project->mode_of_payment = $request->modeofpayment; // check to make sure this field is empty before updating 
                $project->payment_duration = date('d/m/Y', strtotime('+'.$request->paymentduration.' months'));// check to make sure this field is empty before updating 
                $project->save();
                $paymentModel["duration"] = $project->payment_duration;
                $paymentModel["mode_of_payment"] = $project->mode_of_payment ;
                return response()->json(['status'=>'success', 'message'=>"Payment was a success", 'data'=>$paymentModel],200);
            }

            return response()->json(['status'=>'success', 'message'=>"Payment was a success", 'data'=>$paymentModel],200);
        }else{
            return response()->json(['status'=>'error', 'message'=>"could not update Project payment status"],400);
        }
        
        
    }

    /**
     * This function is used to create a back dor payment for meshgrid.
     * due to the fact that meshgrid is unique we need to have a special backdoor payment to handle its payment. 
     * Request structure 
     * $request = [
     *  "amount":"233",
     *  "actual_amount":"233",
     *  "typeofpayment":"233",
     *  "modeofpayment":"233",
     *  "paymentduration":"233",
     *  ];
     * Above are the required key and value types required for the request params.
     * 
     * @method mixed backdoorPaymentMeshgrid()
     * @param Request $request
     * @param string $id
     * @return void
     */


    public function backdoorPaymentMeshgrid(Request $request, string $id)
    {
        
        $loggedinuser = auth()->guard('sanctum')->user();
        if(empty($loggedinuser)){
            return response()->json(['status'=>'error', 'message'=>'No user was logged in '],400);
        }
        if((int)$loggedinuser->role !== 1){
            return response()->json(['status'=>'error', 'message'=>'User does not have priv to call this route '.$loggedinuser->role],400);
        }

        // check to make sure project exist
        $project = MeshgridConnections::where('id',$id)->first();
        if(empty($project)){
            return response()->json(['status'=>'error', 'message'=>'project does not exist'],400);
        }

        $validator = Validator::make($request->all(),[
            'amount' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status' => 'error' , 'message'=>'all fields are required' , 'data'=>''],400);
        }

        // check all request data was validated

        // proccess payment
        $amountPaid = $request->input("amount");
        $actualAmount = $request->input("actual_amount");

        // get all payment and check if payment has been compleated 
        $paymentModelPaySum = Payment::where("meshgrid_id",$id)->sum("amount");
        if($amountPaid + $paymentModelPaySum >= $project->amount){
            $project->payment_status = Project::$available;
        }

        $paymentModel = new Payment();
        $paymentModel->project_id = $project->project->project_id;
        $paymentModel->amount = $amountPaid;
        $paymentModel->actual_amount = $actualAmount;
        $paymentModel->order_id = 0;
        $paymentModel->status = Payment::$available;
        $paymentModel->fromwhere = "meshgrid";
        $paymentModel->type_of_payment = $request->typeofpayment;
        
            
        if($paymentModel->save()){
            return response()->json(['status'=>'success', 'message'=>"Payment was a success", 'data'=>$paymentModel],200);
        }else{
            return response()->json(['status'=>'error', 'message'=>"could not update Project payment status"],400);
        }
        
        
    }
}
