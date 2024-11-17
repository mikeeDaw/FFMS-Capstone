<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Contract\Auth;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Contract\Firestore;
use Google\Cloud\Firestore\FirestoreClient;
use Kreait\Firebase\Factory;

class OrderCtrl extends Controller
{

    private $auth;
    private $firestore;
    private $factory;
    public function __construct()
    {
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->auth = $this->factory->createAuth();

        $this->firestore = $this->factory->createFirestore();
    }

    public function index() {

        // Redirect to Home if a user is logged in and verified.
        if(session()->has('uid') && session()->has('verified')){

            if(!session('verified')){
                return redirect('/verifyAccount');
            }

            return view('order.orderStep1');
        } else {
            return redirect('/login/attempt');
        }
    }

    public function ordStep2(){
        // Redirect to Home if a user is logged in.
        if(!session()->has('uid') && !session()->has('Consignee')){
            return redirect('/');
        } else {
            return view('order.orderStep2');
        }      
    }

    public function ordStep3(){
        if(!session()->has('uid') && !session()->has('Consignee') &&
           !session()->has('Package') && session()->has('Charges')){
            return redirect('/');
        } else {
            return view('order.checkout');
        }  
    }

    public function S1store( Request $request){
        $formFields = $request->validate([
            'fname' => ['required', 'alpha:ascii', 'min:2'], 
            'lname' => ['required', 'alpha:ascii', 'min:2' ], 
            'cnum' => ['required', 'numeric', 'digits:11', 'starts_with:09'],
            'email' => ['required', 'email'], 
            'province' => ['required'], 
            'city' => ['required'], 
            'barang' => ['required'], 
            'zipcode' => ['required'],
            'street' => ['required', 'regex:/^[a-zA-Z0-9 ,.-]+$/'],  
            // 'distance' => [],
            // 'route' => []
        ]);
        
        // $formFields['distance'] = (float) $formFields['distance'];

        // Store to Session
        // if($formFields['distance'] > 0 && $formFields['distance'] != null){
        //     $request->session()->put('Consignee', $formFields);
        //     return redirect('/step2');
        // } else{
        //     return back()->with('errMsg', 'Destination Address not Found')->withInput()->withErrors(['addrErr' => 'Address not Found.']);
        // }       

        $request->session()->put('Consignee', $formFields);
        return redirect('/step2');
    }

    public function S2store( Request $request){
        $formFields = $request->validate([
            'itm-desc' => ['required', 'regex:/^[a-zA-Z0-9 .-]+$/', 'min:2'], 
            'itm-quan' => ['required', 'numeric', 'gt:0'], 
            'itm-pack' => ['required'],
            'itm-categ' => ['required'], 
            'itm-value' => ['required','numeric', 'gt:0'], 
            'quant' => ['required','numeric', 'gt:0'], 
            'serv-typ' => ['required'], 
            'weight' => ['required', 'numeric', 'gt:0'],
            'lg' => ['required','numeric', 'gt:0' ],  
            'wd' => ['required', 'numeric', 'gt:0'],
            'hg' => ['required', 'numeric', 'gt:0'],
            'note' => [],
            'distance' => [],
            'route' => [],
            // 'regex:/^[a-zA-Z0-9 .-]+$/'
        ]);

        $formFields['distance'] = (float) $formFields['distance'];
        $formFields['hg'] = (float) $formFields['hg'];
        $formFields['lg'] = (float) $formFields['lg'];
        $formFields['wd'] = (float) $formFields['wd'];
        $formFields['itm-quan'] = (int) $formFields['itm-quan'];
        $formFields['itm-value'] = (float) $formFields['itm-value'];
        $formFields['quant'] = (int) $formFields['quant'];
        $formFields['weight'] = (float) $formFields['weight'];
         
    
        $consig = session('Consignee');
        $request->session()->put('Package', $formFields);

        $db = $this->firestore->database();
        $priceRef = $db->collection('Prices');

        // Get Pricing Documents
        $docRef = $priceRef->document('ServiceType');
        $gasRef = $priceRef->document('ServicePerKM');
        $packRef = $priceRef->document('PackagingCost');
        $chrgRef = $priceRef->document('Charges');
        // Put their data in Variable.
        $servType = $docRef->snapshot()->data();
        $gas = $gasRef->snapshot()->data();
        $pack = $packRef->snapshot()->data();
        $charges = $chrgRef->snapshot()->data();

        /* Calculation of Charges */

        // FC = Service Type + Packaging Type + ( Qty * Weight) +
        //      (Distance * Gas Needed)
        $data['freightChrg'] = round($servType[$formFields['serv-typ']] + $pack[$formFields['itm-pack']] + ($formFields['quant'] * $formFields['weight']) + ($formFields['distance'] * $gas[$formFields['serv-typ']]),2);

        // IC = Insurance Percent * Declared Value
        $data['insurFee'] = round($charges['Insurance'] * $formFields['itm-value'],2);
        
        // SC = Service Type * Service Charge Percentage
        $data['servChrg'] = round($servType[$formFields['serv-typ']] * $charges['ServiceChg'],2);
        
        // Sub = FC + IC + SC
        $data['subtotal'] = round($data['freightChrg'] + $data['insurFee'] + $data['servChrg'],2);

        // Total = Sub + ( Sub * Cost Percentage )
        $data['total'] = round($data['subtotal'] + ($data['subtotal'] * $charges['TotalCost']), 2);
        
        $request->session()->put('Charges', $data);

        return redirect('/step3');

    }

    public function S3store( Request $request){

        session()->forget(['Consignee', 'Package', 'Charges']);

        return redirect("/")->with('message', "Order Successfully Created.");

        
    }

    public function showOrders(){
        
        
        $data['newOrders'] = [];
        $data['orderList'] = [];
        $data['newCount'] = 0;

        // Set Todays Date for getting New Orders & set timezone to Singapore
        $now = new \DateTime(); // $now->format('Y-m-d')
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
 
        // Connect to database
        $db = $this->firestore->database();
        $colRef = $db->collection('Orders');

        $allDocs = $colRef->select(['CreatedAt', 'pay', 'payStatus', 'itm-desc', 'itm-categ', 'status','timeCreated'])->orderBy('CreatedAt', 'DESC')->documents();

        foreach($allDocs as $doc){
            $docData = $doc->data();
            $docData['orderID'] = $doc->id();

            // Get Orders created in the same day
            if($docData['CreatedAt'] == $newGMT->format('Y-m-d') ){
                array_push($data['newOrders'], $docData);
                $data['newCount'] += 1;
            } else{
                array_push($data['orderList'], $docData);
            }
        }

        return view("order.ordersTab", $data);
    }

    public function orderDetails($orderID){

        $db = $this->firestore->database();
        $orderDoc = $db->collection('Orders')->document($orderID)->snapshot();
        
        if(!$orderDoc->exists()){
            return back()->with('errMsg', 'Order not Found.');
        }

        $orderData = $orderDoc->data();
        

        $data['order'] = $orderData;
        $data['order']['orderID'] = $orderID;

        // Check if there is a receipt uploaded.
        if(!$orderData['rpt_img_name']){

            $data['imgExists'] = false;
        }else{

            $expire = new \DateTime('tomorrow');
            $storage = app('firebase.storage');
            $default = $storage->getBucket();
            $imageRef = $default->object('receipts/'.$orderData['User']."/".$orderID.'/'.$orderData['rpt_img_name']);

            if($imageRef->exists()){
                $image = $imageRef->signedUrl($expire);
                $data['imgExists'] = true;
                $data['order']['receipt'] = $image;

            }else{
                $data['imgExists'] = false;
            }

        }

        return view('order.orderInfo', $data);
    }

    public function editPackage($orderID){

        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');

        $orderDoc = $orderRef->document($orderID);
        $orderData = $orderDoc->snapshot()->data();

        $orderData['orderID'] = $orderID;

        $data['order'] = $orderData;


        return view('order.editPackage', $data);
    }

    public function saveOrderUpd(Request $request, $orderID){

        $formFields = $request->validate([
            'weight' => ['numeric', 'gt:0'],
            'height' => ['numeric', 'gt:0'],
            'width' => ['numeric', 'gt:0'],
            'length' => ['numeric', 'gt:0'],
            'packQuant' => ['numeric', 'gt:0'],
            'updPack' => [],
            'updServ' => [],
        ]);

        $db = $this->firestore->database();
        $orderRef = $db->collection('Orders');
        $statRef = $db->collection('Statistics');
        $priceRef = $db->collection('Prices');

        $orderDoc =$orderRef->document($orderID);
        $orderData = $orderDoc->snapshot()->data();

        $servStat = $statRef->document('ServiceStats');
        $packStat = $statRef->document('PackagingStats');
        $servData = $servStat->snapshot()->data();
        $packData = $packStat->snapshot()->data();

        /* Updating Stats Collection */
        if($orderData['serv-typ'] != $formFields['updServ']){
            $servStat->update([
                ['path' => $orderData['serv-typ'], 'value' => $servData[$orderData['serv-typ']] - 1],
                ['path' => $formFields['updServ'], 'value' => $servData[$formFields['updServ']] + 1 ],
            ]);
        }
        if($orderData['itm-pack'] != $formFields['updPack']){
            $packStat->update([
                ['path' => $orderData['itm-pack'], 'value' => $packData[$orderData['itm-pack']] - 1],
                ['path' => $formFields['updPack'], 'value' => $packData[$formFields['updPack']] + 1 ],
            ]);
        }

        // Get Pricing Documents
        $docRef = $priceRef->document('ServiceType');
        $gasRef = $priceRef->document('ServicePerKM');
        $packRef = $priceRef->document('PackagingCost');
        $chrgRef = $priceRef->document('Charges');
        // Put their data in Variable.
        $servType = $docRef->snapshot()->data();
        $gas = $gasRef->snapshot()->data();
        $pack = $packRef->snapshot()->data();
        $charges = $chrgRef->snapshot()->data();

        /* Calculation of Charges */

        // FC = Service Type + Packaging Type + ( Qty * Weight) +
        //      (Distance * Gas Needed)
        $freightChrg = $servType[$formFields['updServ']] + $pack[$formFields['updPack']] + ($formFields['packQuant'] * $formFields['weight']) + ($orderData['distance'] * $gas[$formFields['updServ']]);

        // IC = Insurance Percent * Declared Value
        $insurFee = $charges['Insurance'] * $orderData['itm-value'];
        
        // SC = Service Type * Service Charge Percentage
        $servChrg = $servType[$formFields['updServ']] * $charges['ServiceChg'];
        
        // Sub = FC + IC + SC
        $subtotal = $freightChrg + $insurFee + $servChrg;

        // Total = Sub + ( Sub * Cost Percentage )
        $total = $subtotal + ($subtotal * $charges['TotalCost']);

        $orderDoc->update([
            ['path' => 'quant', 'value' => $formFields['packQuant']],
            ['path' => 'lg', 'value' => $formFields['length']],
            ['path' => 'wd', 'value' => $formFields['width']],
            ['path' => 'hg', 'value' => $formFields['height']],
            ['path' => 'weight', 'value' => $formFields['weight']],
            ['path' => 'serv-typ', 'value' => $formFields['updServ']],
            ['path' => 'itm-pack', 'value' => $formFields['updPack']],
            ['path' => 'freightChrg', 'value' => round($freightChrg,2)],
            ['path' => 'servChrg', 'value' => round($servChrg,2)],
            ['path' => 'subtotal', 'value' => round($subtotal,2)],
            ['path' => 'total', 'value' => round($total,2)],
            ['path' => 'balance', 'value' => round($total,2) ],
        ]);

        return redirect('/dash/orders/'.$orderID)->with('message', 'Update Successful!');
        
    }

    public function dismissOrder($shipID){
        
        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');
        $shipDoc = $shipRef->document($shipID);
        $shipSnap = $shipDoc->snapshot();

        $progress = request('current');

        if(!$shipSnap->exists()){
            return back();
        }

        $statRef = $db->collection('Statistics');
        $shipStat = $statRef->document('ShipmentStats');
        $statData = $shipStat->snapshot()->data();

        $orderID = $shipSnap->data()['orderID'];
        $orderDoc = $orderRef->document($orderID);

        $now = new \DateTime();
        $tz = 'Asia/Singapore';
        $newGMT = $now->setTimezone(new \DateTimeZone($tz));

        $shipDoc->update([
            ['path' => 'cancelled', 'value' => $newGMT->format('Y-m-d H:i')],
        ]);
        $orderDoc->update([
            ['path' => 'cancelReason', 'value' => 'Order dismissed'],
            ['path' => 'status', 'value' => 2],
        ]);

        $shipStat->update([
            ['path' => $progress, 'value' => $statData[$progress] - 1],
            ['path' => 'cancelled', 'value' => $statData['cancelled'] + 1]
        ]);

        return back()->with('message', 'Order Dismissal Successful!');
    }

    public function uploadPayment( Request $request, $orderID){
        $formFields = $request->validate([
            'receipt' => ['required', 'image'],
        ]);

        $image = $request->file('receipt');
        $name = $image->getClientOriginalName();
        $storagePath = 'receipts/'.session('uid')."/".$orderID."/";
        $file = $name;
        
        $image->storeAs('receipts', $name);
        $uploaded = Storage::get("//receipts//".$name);

        if($uploaded){
            // If Successully Uploaded.
            $storage = app('firebase.storage');
            $default = $storage->getBucket();
            $default->upload($uploaded, ['name' => $storagePath.$file]);
        } else{
            // Error Happened.
        }

        $now = new \DateTime();
        $tz = 'Asia/Singapore';
        $newGMT = $now->setTimezone(new \DateTimeZone($tz));

        $db = $this->firestore->database();
        $orderCol = $db->collection('Orders');

        $updateData = [
            ['path' => 'pay_date', 'value' => $newGMT->format('Y-m-d H:i')],
            ['path' => 'rpt_img_name', 'value' => $name],
        ];

        $orderRef = $orderCol->document($orderID);
        $orderRef->update($updateData);

        return back()->with('message', 'Receipt Sucessfully Uploaded!');
    }

    public function cancelOrder( Request $request, $shipID){
        $reason = $request->validate([
            'reason' => ['required'],
        ]);

        $currProg = request('current');

        $db = $this->firestore->database();
        $shipDoc = $db->collection('Shipments')->document($shipID);
        $statRef = $db->collection('Statistics');
        $shipStat = $statRef->document('ShipmentStats');
        $statData = $shipStat->snapshot()->data();
        $shipSnap = $shipDoc->snapshot();

        // If Document Doesn't Exist
        if(!$shipSnap->exists()){
            return back();
        }

        $shipData = $shipSnap->data();
        $orderDoc = $db->collection('Orders')->document($shipData['orderID']);
        $notifDoc = $db->collection('OrgNotif');

        $now = new \DateTime();
        $tz = 'Asia/Singapore';
        $newGMT = $now->setTimezone(new \DateTimeZone($tz));

        $shipDoc->update([
            ['path' => 'cancelled', 'value' => $newGMT->format('Y-m-d H:i')],
        ]);
        $orderDoc->update([
            ['path' => 'cancelReason', 'value' => $reason['reason']],
            ['path' => 'status', 'value' => 2],
        ]);

        $shipStat->update([
            ['path' => $currProg, 'value' => $statData[$currProg] - 1],
            ['path' => 'cancelled', 'value' => $statData['cancelNotice'] + 1 ],
        ]);

        // Make Cancel Notif
        $notifData = [
            'timestamp' => $newGMT->format('Y-m-d H:i'),
            'title' => 'Order Cancelled',
            'orderID' => $shipData['orderID'],
            'dismissed' => false,
            'body' => "Order: ".$shipData['orderID']." was cancelled by the customer."
        ];
        $notifDoc->add($notifData);

        return back()->with('message', 'Order Cancelled Successfully.');


    }

    public function verifyPay(Request $request){

        $payment = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
            'payDate' => ['required'],
            'refNum' => ['required', 'alpha_num' ],
            'shipID' => [],
        ]);

        $payDate = $payment['payDate'];
        $weekNum = $this->weekMonthHelper(strtotime($payDate));

        $db = $this->firestore->database();
        $saleRef = $db->collection('Sales');
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');
        $statRef = $db->collection('Statistics');

        // Update Status in Shipments
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
        $dateToday = $newGMT->format('Y-m-d H:i');

        $updVal = [
            ['path' => 'payVerif', 'value' => $dateToday],
        ];

        $shipDoc = $shipRef->document($payment['shipID']);
        $shipDoc->update($updVal);

        // Subtract Paid to the Balance
        $orderID = $shipDoc->snapshot()->data()['orderID'];
        $orderDoc =  $orderRef->document($orderID);
        $currBal = $orderDoc->snapshot()->data()['balance'];

        $orderDoc->update([
            ['path' => 'balance', 'value' => $currBal - $payment['amount'] ],
        ]);

        // Adding Amount to Sales Table
        $addData = [
            'amount' => $payment['amount'],
            'orderID' => $orderID,
            'referenceNum' => $payment['refNum'],
        ];

        $weekDoc = $saleRef->document(date('Y', strtotime($payDate)))->collection(date('F',strtotime($payDate)))
                    ->document("Week$weekNum");
        $salesCol = $weekDoc->collection(date('m-d', strtotime($payDate)));
        $salesCol->add($addData);

        if(!isset($weekDoc->snapshot()->data()['week'])){
        // Put Week Year
            $weekDoc->set([
                    'week' => $this->weekYear(strtotime($payDate))
            ]);
        }

        // Update Statistics Collection
        $shipstat = $statRef->document('ShipmentStats');
        $statData = $shipstat->snapshot()->data();

        $shipstat->update([
            ['path' => 'awaitPay', 'value' => $statData['awaitPay'] - 1],
            ['path' => 'payVerif', 'value' => $statData['payVerif'] + 1],
        ]);
        
        return back()->with('message', 'Payment Succesfully Verified!');


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
