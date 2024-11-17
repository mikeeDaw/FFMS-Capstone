<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class ShipmentCtrl extends Controller
{
    private $factory;
    private $firestore;

    public function __construct(){
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->firestore = $this->factory->createFirestore();
    }

    public function showShipments(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // Getting References and documents in Firebase
        $db = $this->firestore->database();
        $statRef = $db->collection('Statistics');
        $statData = $statRef->document('ShipmentStats')->snapshot()->data();

        $currState = request('progress') ?? 'apprOrder';

        $nextState = match($currState){
            'outForDelivery' => 'completed',
            'forShipping' => 'outForDelivery',
            'underExamine' => 'forShipping',
            'arrived' => 'underExamine',
            'payVerif' => 'arrived',
            'awaitPay' => 'payVerif',
            'apprOrder' => 'ordApped',
            default => '',
        };

        $data['counts'] = $statData;
        $data['progress'] = $currState;
        $data['nextProg'] = $nextState;

        return view('shipment.shipments', $data);
    }

    public function updateStatus( Request $request, $progress){
        return redirect("/shipments/?progress=$progress")->with('message', 'Shipment Status Updated!');
    }

    public function updateHelper($shipID, $currState, ...$toUpdateField){
        
        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $statRef = $db->collection('Statistics');
        $shipStatDoc = $statRef->document('ShipmentStats');
        $addToStat = 0;
        // Today's Date Instance
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
        $dateToday = $newGMT->format('Y-m-d H:i');

        if(count($toUpdateField) == 1){

            foreach($shipID as $id){
                $shipDoc = $shipRef->document($id);

                $updData = [
                    ['path' => $toUpdateField[0], 'value' => $dateToday],
                ];
                $shipDoc->update($updData);
                $addToStat += 1;

            }
        } else {

            foreach($shipID as $id){
                $shipDoc = $shipRef->document($id);
                //dd($toUpdateField, end($toUpdateField));
                foreach($toUpdateField as $nextVals){
                    $updData = [
                        ['path' => $nextVals, 'value' => $dateToday],
                    ];
                    $shipDoc->update($updData);
                }
                $addToStat += 1;
            }

        }

        $shipStatData = $shipStatDoc->snapshot()->data();
        $statUpd = [
            ['path' =>  $currState, 'value' => $shipStatData[$currState] - $addToStat],
            ['path' => end($toUpdateField), 'value' => $shipStatData[end($toUpdateField)] + $addToStat],
        ];
        $shipStatDoc->update($statUpd);

    }

    public function completedUpdater($shipID, $currState, $toUpdateField){

        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $statRef = $db->collection('Statistics');
        $shipStatDoc = $statRef->document('ShipmentStats');
        $addToStat = 0;

        // Today's Date Instance
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
        $dateToday = $newGMT->format('Y-m-d H:i');

        $driverRef = $db->collection('Drivers');
        $vehicleRef = $db->collection('Vehicles')->document('List');
        $orderRef = $db->collection('Orders');
        $salesRef = $db->collection('Sales');

        foreach($shipID as $id){
            $shipDoc = $shipRef->document($id);
            
            $shipData = $shipDoc->snapshot()->data();
            $driverDocu = $driverRef->document($shipData['driverID']);
            $driverVehiDoc = $driverDocu->collection('Vehicles')->document('vehicleList');
            $vehiFreqData = $driverVehiDoc->snapshot()->data();
            $orderDocu = $orderRef->document($shipData['orderID']);
            $payMeth = $orderDocu->snapshot()->data()['pay'];
            $servType = $orderDocu->snapshot()->data()['serv-typ'];
            $vehiDocu = $vehicleRef->collection($servType)->document($shipData['vehicleID']);
            
            $drivUpd = [
                ['path' => 'available', 'value' => true],
                ['path' => 'lastDelivery', 'value' => $newGMT->format('Y-m-d')],
            ];

            $vehiUpd = [
                ['path' => 'available', 'value' => true],
                ['path' => 'last_used', 'value' => $newGMT->format('Y-m-d')],
            ];

            $vehiFreqUpd = [
                ['path' => $servType, 'value' => $vehiFreqData[$servType] + 1],
            ];

            $driverDocu->update($drivUpd);
            $vehiDocu->update($vehiUpd);
            $driverVehiDoc->update($vehiFreqUpd);

            // Adding Amount to Sales if COD
            if ($payMeth == 'COD'){
                $addData = [
                    'amount' => $orderDocu->snapshot()->data()['balance'],
                    'orderID' => $orderDocu->id(),
                ];

                $weekNum = $this->weekMonthHelper(strtotime(date('Y-m-d')));
                $weekDoc = $salesRef->document(date('Y'))->collection(date('F'))
                            ->document("Week$weekNum");
                $salesCol = $weekDoc->collection(date('m-d'));
                $salesCol->add($addData);
            }

            // Update Order Document
            $orderDocu->update([
                ['path' => 'status', 'value' => 1],
                ['path' => 'balance', 'value' => 0],
            ]);
                        
            $updData = [
                ['path' => $toUpdateField[0], 'value' => $dateToday],
            ];
            $shipDoc->update($updData);
            $addToStat += 1;

        }
         
        $shipStatData = $shipStatDoc->snapshot()->data();
        $statUpd = [
            ['path' =>  $currState, 'value' => $shipStatData[$currState] - $addToStat],
            ['path' => $toUpdateField, 'value' => $shipStatData[$toUpdateField] + $addToStat],
        ];
        $shipStatDoc->update($statUpd);

        
    }

    // public function shipmentAllocate(){
    //     // Function for showing allocation UI and passing data for manual allocation

    //     if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
    //         return redirect('/');
    //     } elseif (!session('verified')){
    //         return redirect('/verifyAccount');
    //     }


    //     $db = $this->firestore->database();
    //     $shipRef = $db->collection('Shipments');
    //     $orderRef = $db->collection('Orders');
    //     $driverRef = $db->collection('Drivers');
    //     $vehicleRef = $db->collection('Vehicles');

    //     $data['toAllocate'] = [];
    //     $data['allocated'] = [];
    //     $data['freeDrivers'] = [];
    //     $data['freeVehicles'] = [];

    //     // Query to get progress where its not past the 'for shipping' stage.
    //     // NOTE: firestore PHP dont have support yet for 'x != null' in 'where' clause.
    //     $forShipDocs = $shipRef
    //         ->select(['forShipping','driverID', 'vehicleID', 'orderID'])
    //         ->where('outForDelivery', '=', null )
    //         ->where('completed', '=', null )
    //         ->documents();

    //     foreach($forShipDocs as $doc){
    //         $docData = $doc->data();
    //         // If shipment is in 'For shipping stage'
    //         if($doc['forShipping']){

    //             $orderDoc = $orderRef->document($docData['orderID'])->snapshot()->data();
                
    //             $docData['itm-categ'] = $orderDoc['itm-categ'];
    //             $docData['serv-typ'] = $orderDoc['serv-typ'];
    //             $docData['itm-desc'] = $orderDoc['itm-desc'];
    //             $docData['shipmentID'] = $doc->id();

    //             $drivers = $driverRef
    //                 ->select(['Fname', 'Lname'])
    //                 ->where('available', '=', true);

    //             // Checking either theres a driver or vehicle already assigned
    //             // then assigning to groups if their allocated or not.
    //             if( is_null($doc['driverID']) || is_null($doc['vehicleID']) ){
    //                 // Get Driver name if there is a driver allocated
    //                 if(!is_null($docData['driverID'])){
    //                     $drivDoc = $driverRef->document($docData['driverID'])->snapshot()->data();
    //                     $docData['drivName'] = $drivDoc['Fname']." ".$drivDoc['Lname']; 
    //                 }
    //                 $data['toAllocate'] =  array_merge($data['toAllocate'], [$docData]);
    //             } else{
    //                 // Get Driver name
    //                 $drivDoc = $driverRef->document($docData['driverID'])->snapshot()->data();
    //                 $docData['drivName'] = $drivDoc['Fname']." ".$drivDoc['Lname'];
    //                 $data['allocated'] = array_merge($data['allocated'], [$docData]);
    //             }

    //         }
    //     }

    //     // Get Available Drivers
    //     $drives = $driverRef->select(['Fname','Lname','can_TrackHead','can_Chassis40', 'can_Chassis20', 'can_Truck10W','can_ClVan6', 'can_ClVan4', 'can_WingVan'])->where('available', '=', true)->documents();
    //     foreach($drives as $driver){
    //         $docData = $driver->data();
    //         $docData['driverID'] = $driver->id();
    //         $data['freeDrivers'] = array_merge($data['freeDrivers'], [$docData]);
    //     }

    //     // Get Available Vehicles
    //     $vehicles = $vehicleRef->document('List')->collections();
    //     foreach($vehicles as $vehiType){
    //         $data['freeVehicles'][$vehiType->id()] = [];
    //         $freePerType = $vehiType->where('available', '=', true)->documents();

    //         foreach($freePerType as $freeVehi){
    //             $docData = [];
    //             $docData['vehicleID'] = $freeVehi->id();
    //             $data['freeVehicles'][$vehiType->id()] = array_merge($data['freeVehicles'][$vehiType->id()], [$docData]);
    //         }
    //     }

    //     return view('shipment.allocation', $data);
    // }

    public function shipmentAllocate(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $batchRef = $db->collection('DelivBatch');

        $batchDocs = $batchRef->where('allocated', '=', false)->documents();
        $batches = [];
        $servTyps = [];
        foreach($batchDocs as $bDoc){
            $bchData = $bDoc->data();
            $batches[$bDoc->id()] = $bchData;
            if( !in_array($bchData['servType'], $servTyps) ){
                array_push($servTyps, $bchData['servType']);
            }
        }

        $data['batches'] = $batches;
        $data['serviceTyp'] = $servTyps;
        //dd($data);
        return view('shipment.allocation', $data);

    }

    public function saveAllocation( Request $request){
        // Function for saving allocation request

        $formInput = $request->input();
        unset($formInput['_token']);
        $success = 0;
        //dd($formInput);

        if($formInput['timeCanAllo'] == 'false'){
            return back()->with('errMsg', 'Cannot Assign Resources this Time. Possible from Monday - Saturday between 8:00am and 5:00pm.');
        }

        // Today's Date Instance
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));

        foreach($formInput['manualAllo'] as $key => $arrVals){

            if(!array_key_exists('driver', $arrVals) || !array_key_exists('vehicle', $arrVals)){
                return redirect('/shipments/allocate')->with('errMsg', 'Both Driver and Vehicle should be Allocated.');
            }

        }

        return redirect('/shipments/allocate')->with('message', "Allocation Operations for Shipment/s Done!" );
    }

    // public function saveAllocation( Request $request){
    //     // Function for saving allocation request

    //     $formInput = $request->input();
    //     unset($formInput['_token']);
    //     $success = 0;
        
    //     // Today's Date Instance
    //     $now = new \DateTime();
    //     $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));

    //     // Connect to Database
    //     $db = $this->firestore->database();
    //     $shipRef = $db->collection('Shipments');
    //     $driverRef = $db->collection('Drivers');
    //     $vehiRef = $db->collection('Vehicles')->document('List');
    //     $notifRef = $db->collection('Notifications');


    //     /* !-- MANUAL ALLOCATION --! */
    //     // check if user performed a manual allocation
    //     if (array_key_exists('manualAllo', $formInput)){
    //         // Assign the driver or vehicle to each respective shipment.
            
    //         foreach($formInput['manualAllo'] as $key => $arVals){
                
    //             // Update the shipment document
    //             $shipDoc = $shipRef->document($key);
    //             $shipUpdValues = [];

    //             // Availablity field update to pass
    //             $resUpd = [
    //                     ['path' => 'available', 'value' => false ],
    //                 ];
                
    //             if(array_key_exists('driver', $arVals)){
    //                 array_push($shipUpdValues, ['path' => 'driverID', 'value' => $arVals['driver'] ]);
    //                 $driverRef->document($arVals['driver'])->update($resUpd);

    //                 $addData = [
    //                     'date' => $newGMT->format('Y-m-d'),
    //                     'dismissed' => false,
    //                     'driverID' => $arVals['driver'],
    //                     'time' => $newGMT->format('H:i'),
    //                 ];

    //                 $notifRef->add($addData);
    //             }

    //             if(array_key_exists('vehicle', $arVals)){
    //                 array_push($shipUpdValues, ['path' => 'vehicleID', 'value' => $arVals['vehicle'] ]);
    //                 $vehiRef->collection($arVals['serv-typ'])->document($arVals['vehicle'])->update($resUpd);
    //             }
 
    //             $shipDoc->update($shipUpdValues);
                
    //             // Successful assignment count
    //             $success += 1;
    //         }
    //     } 

    //     /* !-- AUTOMATIC ALLOCATION --! */
    //     // check if user wants to perform a auto allocation to a shipment.
    //     if (array_key_exists('autoAllo', $formInput)){
            
    //         $resUpd = [
    //             ['path' => 'available', 'value' => false ],
    //         ];
            
    //         foreach($formInput['autoAllo'] as $value => $servTyp){

    //             $shipDoc = $shipRef->document($value);
    //             $shipData = $shipDoc->snapshot()->data();

    //             if($shipData['driverID'] == null && $shipData['vehicleID'] == null ) {
    //                 $chosenDID = $this->bestFitDriver($value);
    //                 $chosenVID = $this->bestFitVehicle($value);

    //                 $shipUpd = [
    //                     ['path' => 'driverID', 'value' => $chosenDID],
    //                     ['path' => 'vehicleID', 'value' => $chosenVID]
    //                 ];

    //                 if( !is_null($chosenDID) && !is_null($chosenVID)){

    //                     // Update Shipment Assignment
    //                     $shipDoc->update($shipUpd);
    
    //                     // Update Driver and Vehicle status
    //                     $driverRef->document($chosenDID)->update($resUpd);
    //                     $vehiRef->collection($servTyp)->document($chosenVID)->update($resUpd);
    
    //                     // Add to Notif
    //                     $addData = [
    //                         'date' => $newGMT->format('Y-m-d'),
    //                         'dismissed' => false,
    //                         'driverID' => $chosenDID,
    //                         'time' => $newGMT->format('H:i')
    //                     ];
    //                     $notifRef->add($addData);
    //                     $success += 1;
    
    //                 } else if( is_null($chosenDID) || is_null($chosenVID) ) {
    //                     if( is_null($chosenDID) ){
    
    //                         $shipDoc->update([
    //                             ['path' => 'vehicleID', 'value' => $chosenVID],
    //                         ]);
                            
    //                         $vehiRef->collection($servTyp)->document($chosenVID)->update($resUpd);
                            
    
    //                     } else {
    
    //                         $shipDoc->update([
    //                             ['path' => 'driverID', 'value' => $chosenDID],
    //                         ]);
    
    //                         $driverRef->document($chosenDID)->update($resUpd);
    
    //                         $addData = [
    //                             'date' => $newGMT->format('Y-m-d'),
    //                             'dismissed' => false,
    //                             'driverID' => $chosenDID,
    //                             'time' => $newGMT->format('H:i')
    //                         ];

    //                         $notifRef->add($addData);
    
    //                     }
    
    //                     $success += 1;
    
    //                 }
    //             } else {
    //                 if ( !is_null($shipData['driverID']) ){

    //                     $chosenVID = $this->bestFitVehicle($value);

    //                     if( !is_null($chosenVID) ){
                            
    //                         $shipDoc->update([
    //                             ['path' => 'vehicleID', 'value' => $chosenVID],
    //                         ]);
                            
    //                         $vehiRef->collection($servTyp)->document($chosenVID)->update($resUpd);
    //                         $success += 1;
    //                     }

    //                 } else if ( !is_null($shipData['vehicleID']) ){

    //                     $chosenDID = $this->bestFitDriver($value);

    //                     if( !is_null($chosenDID) ){
                            
    //                         $shipDoc->update([
    //                             ['path' => 'driverID', 'value' => $chosenDID],
    //                         ]);
    
    //                         $driverRef->document($chosenDID)->update($resUpd);
    
    //                         $addData = [
    //                             'date' => $newGMT->format('Y-m-d'),
    //                             'dismissed' => false,
    //                             'driverID' => $chosenDID,
    //                             'time' => $newGMT->format('H:i')
    //                         ];
                            
    //                         $notifRef->add($addData);
    //                         $success += 1;
    //                     }

    //                 }
    //             }
 
    //         }
    //     }
        
    //     /* !-- UNDO RESOURCE ALLOCATION --! */
    //     // check if user wantst to undo assignments.
    //     if (array_key_exists('undo', $formInput)){

    //         $shUpVal = [
    //             ['path' => 'driverID', 'value' => null],
    //             ['path' => 'vehicleID', 'value' => null]
    //         ];

    //         $statusVal = [
    //             ['path' => 'available', 'value' => true]
    //         ];

    //         foreach($formInput['undo'] as $shipID => $servTyp){
    //             $shipDoc = $shipRef->document($shipID);
    //             $docData = $shipDoc->snapshot()->data();

    //             $driverRef->document($docData['driverID'])->update($statusVal);
    //             $vehiRef->collection($servTyp)->document($docData['vehicleID'])->update($statusVal);

    //             $shipDoc->update($shUpVal);

    //             $success += 1;
    //         }
    //     }


    //     return redirect('/shipments/allocate')->with('message', "Allocation Operations for $success Shipment/s Done!" );
    // }

    public function bestFitDriver($shipID){

        // Creating References of Collection and Getting data.
        $db = $this->firestore->database();
        $driverRef = $db->collection('Drivers');
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');

        $orderID = $shipRef->document($shipID)->snapshot()->data()['orderID'];
        $orderData = $orderRef->document($orderID)->snapshot()->data();

        /* Start. */
        $driverPool = [];
        // 1. Get all Drivers who: Is Available, and Can drive the vehicle type.
        $driverDocs = $driverRef->select(['lastDelivery'])
                                ->where('available', '=', true)
                                ->where("can_{$orderData['serv-typ']}", '=', true)
                                ->documents();

        // Error handling if no drivers returned by query
        if(!$driverDocs->rows()){
            return null;
        } 

        foreach($driverDocs as $driver){
            $id = $driver->id();
            $lastDate = $driver->data()['lastDelivery'];

            // Get Vehicle Frequency
            $vehiFreq = $driverRef->document($id)->collection('Vehicles')->document('vehicleList')->snapshot()->data()[$orderData['serv-typ']];

            // Get Ratings document
            $ratingRef = $driverRef->document($id)->collection('Ratings');
            $rating = $ratingRef->documents();

            // Pag may ratings
            if($rating->rows()){
                $ratingCategs = $ratingRef
                                    ->where('category', '=', $orderData['itm-categ'])
                                    ->documents();
                                    
                // Pag may ratings sa isang item category
                if($ratingCategs->rows()){
                    $total = [];
                    foreach($ratingCategs as $rating){
                        $rateData = $rating->data();
                        array_push($total,$rateData['ratingAvg']);
                    }
                    $total = array_sum($total)/count($total);
                    
                    array_push($driverPool,
                        [
                            'driverID' => $id,
                            'frequency' => $vehiFreq,
                            'rating' => $total,
                            'lastDate' => $lastDate,
                        ] );
                } else {
                // Pag wala ratings sa isang category
                    array_push($driverPool,
                        [
                            'driverID' => $id,
                            'frequency' => $vehiFreq,
                            'rating' => 0,
                            'lastDate' => $lastDate,
                        ] );
                }

            } else {
            // Pag Walang ratings
                array_push($driverPool,
                    [
                        'driverID' => $id,
                        'frequency' => $vehiFreq,
                        'rating' => 0,
                        'lastDate' => $lastDate,
                    ] );
            }
           
        }
        
        $sorted = $this->quickSort($driverPool);
        $chosenDriver = end($sorted)['driverID'];
        //dd($driverPool, $sorted, $chosenDriver);

        return $chosenDriver;


    }

    public function quickSort($the_array){
        $less = $greater = [];

        if(count($the_array) < 2){
            return $the_array;
        }

        $pivot_key = key($the_array);
        $pivot = array_shift($the_array);

        foreach($the_array as $value){
            if( $value['frequency'] < $pivot['frequency']){
                $less[] = $value;  
            } else if($value['frequency'] > $pivot['frequency']){
                $greater[] = $value;
            } else {
                if($value['rating'] < $pivot['rating']){
                    $less[] = $value;
                } else if($value['rating'] > $pivot['rating']){
                    $greater[] = $value;
                } else {
                    if($value['lastDate'] <= $pivot['lastDate']){
                        $greater[] = $value;
                    } else {
                        $less[] = $value; 
                    }
                }
            }
        }

        /*
        // Compare and arrange drivers in ascending order
        foreach($the_array as $value){
            if( $value['frequency'] > $pivot['frequency'] &&
                $value['rating'] > $pivot['rating'] &&
                $value['lastDate'] > $pivot['lastDate']){
                $greater[] = $value;
            } else if ($value['rating'] > $pivot['rating']){
                $greater[] = $value;
            } else {
                $lessOrEq[] = $value;
            }
        } */
        return array_merge($this->quickSort($less), [$pivot], $this->quickSort($greater));
    }

    public function bestFitVehicle($shipID){

        // Creating References of Collection and Getting data.
        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');

        $orderID = $shipRef->document($shipID)->snapshot()->data()['orderID'];
        $orderData = $orderRef->document($orderID)->snapshot()->data();

        $vehiRef = $db->collection('Vehicles')->document('List')->collection($orderData['serv-typ']);

        $vehiPool = [];
        // 1. Get all Vehicles that: Is Available, and match service type of order.
        $vehiDocs = $vehiRef->select(['last_used'])
                                ->where('available', '=', true)
                                ->orderBy('last_used', 'ASC')->documents();

        // Error handling if no drivers returned by query
        if(!$vehiDocs->rows()){
            return null;
        } 

        // Arrange vehicles by date of last Delivery
        foreach($vehiDocs as $vehicle){
            $vehiData = $vehicle->data();
            $vehiData['driverID'] = $vehicle->id();

            if(array_key_exists($vehiData['last_used'], $vehiPool)){
                $vehiPool[$vehiData['last_used']] = array_merge(
                    $vehiPool[$vehiData['last_used']],
                    [$vehicle->id()],
                    );
            } else {
                $vehiPool[$vehiData['last_used']] = [$vehicle->id()];
            }
        }

        // Return the vehicle with oldest date of utilization or vehicle without
        // at least 1 assignment.
        $oldestDate = reset($vehiPool);
        $chosenVehi = reset($oldestDate);
        //dd($vehiPool,$oldestDate, $chosenVehi);

        return $chosenVehi;
    }

    public function showCancels(){
        
        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');
     
        $cancelled = $shipRef->select(['cancelled', 'orderID'])
                    ->where('cancelled', '!=', false)
                    ->orderBy('cancelled', 'DESC')
                    ->documents(); 

        $data['canCount'] = count($cancelled->rows());
        $data['shipments'] = [];

        foreach($cancelled as $doc){
            $docData = $doc->data();
            $orderData = $orderRef->document($docData['orderID'])->snapshot()->data();
            $docData['customerName'] = $orderData['fname']." ".$orderData['lname'];
            $docData['reason'] = $orderData['cancelReason'];
            $docData['itemDesc'] = $orderData['itm-desc'];
            $docData['dateUpd'] = $docData['cancelled'];
            $docData['shipmentID'] = $doc->id();

            $data['shipments'] = array_merge(
                                    $data['shipments'],
                                    [$docData],
                                );
        }

        return view('shipment.cancellation', $data);
    }

    public function updateCancel(Request $request){

        $field = $request->all();
        unset($field['_token']);

        // Today's Date Instance
        $now = new \DateTime();
        $newGMT = $now->setTimezone(new \DateTimeZone('Asia/Singapore'));
        $dateToday = $newGMT->format('Y-m-d H:i');

        $db = $this->firestore->database();
        $shipRef = $db->collection('Shipments');
        $orderRef = $db->collection('Orders');
        $statRef = $db->collection('Statistics');
        
        $shipStat = $statRef->document('ShipmentStats');
        $statData = $shipStat->snapshot()->data();

        $success = 0;

        if( array_key_exists('RvChecks', $field)){

            $shipUpd = [
                ['path' => 'cancelled', 'value' => $dateToday],
            ];
            $ordUpd = [
                ['path' => 'status', 'value' => 2]
            ];

            foreach($field['RvChecks'] as $shipID){
                $shipDoc = $shipRef->document($shipID);
                $orderID = $shipDoc->snapshot()->data()['orderID'];
                $orderDoc = $orderRef->document($orderID);

                $shipDoc->update($shipUpd);
                $orderDoc->update($ordUpd);
                $success += 1; 
            }

            $shipStat->update([
                ['path' => 'cancelNotice', 'value' => $statData['cancelNotice'] - $success],
                ['path' => 'cancelled', 'value' => $statData['cancelled'] + $success],
            ]);
        }

        return back()->with('message', "$success Order/s Cancelled.");

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


