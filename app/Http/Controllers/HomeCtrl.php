<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class HomeCtrl extends Controller
{

    private $factory;
    private $auth;
    private $firestore;

    public function __construct()
    {
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->auth = $this->factory->createAuth();

        $this->firestore = $this->factory->createFirestore();
    }

    public function index(){
        // $ass = $this->auth->getUser(session('uid'));
        //dd(session()->all());
        if(session()->has('Consignee')){
            session()->forget('Consignee');
        }
        if(session()->has('Package')){
            session()->forget(['Package', 'Charges']);
        }
        
        return view('/home/homepage');
    }

    public function login(){

        return view('/logforms/loginform');
    }

    public function profile(){

        if(!session()->has('uid')){
            return redirect('/login/attempt');
        }

        // Get Orders of User
        $db = $this->firestore->database();
        $uid = session('uid');
        $ordRef = $db->collection('Orders');
        // $shipRef = $db->collection('Shipments');
        // $shipSnap = $shipRef->where('customerID',"=", $uid)->orderBy('orderCreated', 'DESC')->documents();
        $orderSnap = $ordRef->select(['CreatedAt', 'itm-categ', 'itm-desc', 'status'])
                            ->where('User', '=', $uid)
                            ->orderBy('CreatedAt', 'DESC')
                            ->documents();

        $data['pending'] = 0;
        $data['orders'] = array();
        // foreach($shipSnap as $document){
        //     $ordDoc = $ordRef->document($document->data()['orderID']);
        //     $ordData = $ordDoc->snapshot()->data();
        //     $ordData['shipID'] = $document->id();
        //     $ordData['orderID'] = $ordDoc->id();
        //     array_push($data['orders'], $ordData);
        //     if($ordData['status'] == 0){
        //         $data['pending'] += 1;
        //     }
        // }
        foreach($orderSnap as $document){
            $ordData = $document->data();
            $ordData['orderID'] = $document->id();
            array_push($data['orders'], $ordData);
            if($ordData['status'] == 0){
                $data['pending'] += 1;
            }
        }

        return view('home.profile', $data);
    }
    
    public function pfUpd(Request $request){
        
        $formFields = $request->validate([
            'Fname' => ['required', 'regex:/^[a-zA-Z -]+$/', 'min:2'], 
            'Lname' => ['required', 'regex:/^[a-zA-Z -]+$/', 'min:2' ], 
            'Contact' => ['required', 'numeric','digits:11'],
            'DOB' => ['required'],
        ]);

        $upVal = array();
        foreach($formFields as $key => $value){
            array_push($upVal,['path'=> $key, 'value'=> $value]);
        }
   
        $db = $this->firestore->database();
        $docRef = $db->collection(session('user')['Userlevel']."s")->document(session('uid'));
        $docRef->update($upVal);

        $request->session()->put('user', $docRef->snapshot()->data());

        return redirect('/profile')->with('message','Update Successful.');
    }

    public function passUpdate( $email ){

        $this->auth->sendPasswordResetLink($email);
        return back()->with('message', 'Password Reset Link Sent!');

    }

    public function showOrderInfo($shipID){

        if(!session()->has('uid')){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // Get shipment and order details
        $db = $this->firestore->database();
        $orderDoc = $db->collection('Orders')->document($shipID);
        $shipRef = $db->collection('Shipments');
        $orderSnap = $orderDoc->snapshot();
        
       // dd("hey");
        // If Document Doesn't Exist
        if(!$orderSnap->exists()){
            return redirect('/profile');
        }

        $orderData = $orderSnap->data();
        $orderData['orderID'] = $orderDoc->id();

        // if($orderData['rpt_img_name'] != null){

        //     $expire = new \DateTime('tomorrow');
        //     $storage = app('firebase.storage');
        //     $default = $storage->getBucket();
        //     $imageRef = $default->object('receipts/'.$orderData['User']."/".$orderDoc->id().'/'.$orderData['rpt_img_name']);

        //     if($imageRef->exists()){
        //         $image = $imageRef->signedUrl($expire);
        //         $receipt['imgExists'] = true;
        //         $receipt['receipt'] = $image;

        //     }else{
        //         $receipt['imgExists'] = false;
        //     }
        // } else {
        //     $receipt['imgExists'] = false;
        //     $receipt['receipt'] = null;
        // }


        $amount = 0;
        $desc = '';
        
        // Payment API

        if($orderData['pay'] == 'Online'){
            $amount = round($orderData['total'],2) * 100;
            $desc = "Full Payment of Order ID: ".$orderDoc->id().".";
        } else {
            $amount = round(($orderData['total'] / 2), 2) * 100;
            $desc = "Down Payment (50%) of Order ID: ".$orderDoc->id().".";
        }

        $client = new \GuzzleHttp\Client();

        $responser = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
            'body' => '{"data":{"attributes":{"billing":{"name":"'.session('user')['Fname'].' '.session('user')['Lname'].'" ,"email":"'.session('user')['Email'].'"},"line_items":[{"currency":"PHP","amount":'.$amount.',"description":"Some Desc","name":"Shipment of: '.$orderData['itm-desc'].'","quantity":1}],"payment_method_types":["card","gcash","dob"],"billing":{"address":{"line1":"'.$orderData['street'].'", "city":"'.$orderData['city'].'","country":"PH","state":"'.$orderData['province'].'", "postal_code":"'.$orderData['zipcode'].'"},"email":"'.session('user')['Email'].'", "name":"'.session('user')['Fname'].' '.session('user')['Lname'].'", "phone" : "'.substr(session('user')['Contact'],1).'" }, "send_email_receipt":true, "show_description":true, "show_line_items":true, "description":"'.$desc.'", "success_url":"http://127.0.0.1:8000/paymentSuccess?order='.$orderDoc->id().'"}}}',

            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => 'Basic '.env('AUTH_PAY')
            ],
    
        ]);

        $xd = json_decode($responser->getBody());

        $data['checkoutURL'] = $xd->data->attributes->checkout_url;
        $data['singleOrder'] = $orderData;
        
        return view('order.profileOrder', $data);
    }

    public function paymentSuccess(){

        $orderID = request('order');
        // Today's Date Instance
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
        $dateToday = $newGMT->format('Y-m-d H:i');
        
        $db = $this->firestore->database();

        $shipRef = $db->collection('Shipments');
        $orderData = $db->collection('Orders')->document($orderID)->snapshot()->data();
        $shipQuer = $shipRef->where('orderID', '=', $orderID)->documents();
        $shipDocq = $shipQuer->rows()[0];
        
        if($orderData['pay'] == "COD"){
            $paid = round(($orderData['total'] / 2), 2 );
        } else {
            $paid = $orderData['total'];
        }

        // For sales table
        $weekNum = $this->weekMonthHelper(strtotime($dateToday));

        $data['monthWord'] = date('F');
        $data['monthNum'] = date('m');
        $data['year'] = date('Y');
        $data['day'] = date('d');
        $data['weekYr'] = $this->weekYear(strtotime(date('Y-m-d')));
        $data['weekNum'] = $weekNum;
        $data['orderID'] = $orderID;
        $data['datePay'] = $dateToday;
        $data['paid'] = $paid;
        $data['shipID'] = $shipDocq->id();
        $data['orderData'] = $orderData;

        return view('order.paidSuccess', $data);
    }

    public function weekMonthHelper($date){
        $firstOfMonth = strtotime(date('Y-m-01', $date));
        return $this->weekYear($date) - $this->weekYear($firstOfMonth) + 1;
    }

    public function weekYear($date){
        $weekYear = intval(date('W', $date));
        if(date('n',$date) == '1' && $weekYear > 51 ){
            return 1;
        } else if (date('n', $date) == '12' && $weekYear == 1){
            return 53;
        } else {
            return $weekYear;
        }
    }
}
