<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Cache;

class DashCtrl extends Controller
{
    private $factory;
    private $auth;
    private $firestore;
    
    public function __construct(){
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->auth = $this->factory->createAuth();

        $this->firestore = $this->factory->createFirestore();
    }

    public function index(){

        // Check User Level to Access Dashboard
        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $statRef = $db->collection('Statistics');

        $data['statuses'] = $statRef->document('ShipmentStats')->snapshot()->data();

        return view("dashboard.dashboard", $data);

    }

    public function accAdmin(){
        // Function for showing admin accounts

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $fields = ['Fname','Lname','Email','Contact'];
        $data = $this->tableRetrieve('Admins', $fields, true);
        
        return view('dashboard.accounts.adminDash', $data);

    }

    public function accUsers(){ 
        // Function for showing user accounts

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $fields = ['Fname','Lname','Email'];
        $data = $this->tableRetrieve('Users', $fields, true);

        return view('dashboard.accounts.userDash', $data);
    }

    public function accDrivers(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // Function for showing driver accounts
        $fields = ['Fname', 'Lname', 'Contact', 'lastDelivery']; 
        $data = $this->tableRetrieve('Drivers', $fields);

        return view('dashboard.accounts.driverDash', $data);
    }

    public function accStaffs(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // Function for showing staff accounts
        $fields = ['Fname', 'Lname', 'Contact', 'Email'];
        $data = $this->tableRetrieve('Staffs', $fields);
        return view('dashboard.accounts.staffDash', $data);
    }

    public function adminStore(Request $request){
        // Function when user creation form was submitted

        $formFields = $request->validate([
            'fname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2'], 
            'lname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2' ], 
            'conum' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'email'],
            'dob' => ['required'],
            'ur_pass' => ['required','min:8','confirmed'],
            'ur_pass_confirmation' => ['required']
        ]);
        // store necessary data for firebase auth user creation
        // Create user in firebase auth with the data in $user
        try{

            $user = [
                'email' => $formFields['email'],
                'emailVerified' => false,
                'password' => $formFields['ur_pass'],
                'Display Name' => $formFields['fname']." ".$formFields["lname"],
            ];

            $createdUser = $this->auth->createUser($user);
        } catch (Exception $e){
            return back()->with('errMsg', $e->getMessage());
        }

        // Send Email Verification
        $this->auth->sendEmailVerificationLink($formFields['email']);

        // Store info in database
        $db = $this->firestore->database();

        $colRef = $db->collection('Admins');
        $colRef->document($createdUser->uid)->set([
            'Fname' => $formFields['fname'],
            'Lname' => $formFields['lname'],
            'Contact' => "0".$formFields['conum'],
            'Email' => $formFields['email'],
            'DOB' => $formFields['dob'],
            'Userlevel' => 'Admin',
            'addresses' => null,
        ]);

        return redirect('/dashboard/admin')->with('message', 'Admin Account Created.');
    }

    public function userStore( Request $request){
        // Function when user creation form was submitted

        $formFields = $request->validate([
            'fname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2'], 
            'lname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2' ], 
            'conum' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'email'],
            'dob' => ['required'],
            'ur_pass' => ['required','min:8','confirmed'],
            'ur_pass_confirmation' => ['required']
        ]);
 
        try{

            // store necessary data for firebase auth user creation
            $user = [
                'email' => $formFields['email'],
                'emailVerified' => false,
                'password' => $formFields['ur_pass'],
                'Display Name' => $formFields['fname']." ".$formFields["lname"],
            ];
            // Create user in firebase auth with the data in $user
            $createdUser = $this->auth->createUser($user);
            
        } catch (Exception $e){
            return back()->with('errMsg', $e->getMessage());
        }
        
        // Send Email Verification
        $this->auth->sendEmailVerificationLink($formFields['email']);

        // Store info in database
        $db = $this->firestore->database();

        $colRef = $db->collection('Users');
        $colRef->document($createdUser->uid)->set([
            'Fname' => $formFields['fname'],
            'Lname' => $formFields['lname'],
            'Contact' => "0".$formFields['conum'],
            'Email' => $formFields['email'],
            'DOB' => $formFields['dob'],
            'Userlevel' => 'User',
            'addresses' => null,
        ]);

        return redirect('/dashboard/users')->with('message', 'User Account Created.');
    }

    public function driverStore( Request $request ){
        // Function when driver creation form was submitted

        $formFields = $request->validate([
            'fname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2'], 
            'lname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2' ], 
            'conum' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'email'],
            'dob' => ['required'],
            'dr_pass' => ['required','min:8','confirmed'],
            'dr_pass_confirmation' => ['required'],
            'VE' => [ 'required', 'array', 'min:1', 'max:7'],
        ]);

        try{
            // store necessary data for firebase auth user creation
            $user = [
                'email' => $formFields['email'],
                'emailVerified' => false,
                'password' => $formFields['dr_pass'],
                'Display Name' => $formFields['fname']." ".$formFields["lname"],
            ];

            // Create user in firebase auth with the data in $user
            $createdUser = $this->auth->createUser($user);
            
        } catch (Exception $e){
            return back()->with('errMsg', $e->getMessage());
        }
        
        
        // Send Email Verification
        $this->auth->sendEmailVerificationLink($formFields['email']);

        // Store info to 'Driver' Collection.
        $db = $this->firestore->database();

        $colRef = $db->collection('Drivers');
        $colRef->document($createdUser->uid)->set([
            'Fname' => $formFields['fname'],
            'Lname' => $formFields['lname'],
            'Contact' => "0".$formFields['conum'],
            'Email' => $formFields['email'],
            'DOB' => $formFields['dob'],
            'Userlevel' => 'Driver',
            'available' => true,
            'lastDelivery' => null,
            // Driver's ability to drive a vehicle based on the checkbox checked.
            'can_TrackHead' => in_array("Tractor Head", $formFields['VE']) ? true : false,
            'can_Chassis40' => in_array("40ft Chassis", $formFields['VE']) ? true : false,
            'can_Chassis20' => in_array("20ft Chassis", $formFields['VE']) ? true : false,
            'can_Truck10W' => in_array("10 Wheeler Truck", $formFields['VE']) ? true : false,
            'can_ClVan6' => in_array("6 Wheeler Closed Van", $formFields['VE']) ? true : false,
            'can_ClVan4' => in_array("4 Wheeler Closed Van", $formFields['VE']) ? true : false,
            'can_WingVan' => in_array("Wing Van", $formFields['VE']) ? true : false,
            
        ]);

        // Create Document for Vehicle Frequency
        $vehiclesRes = $colRef->document($createdUser->uid)->collection('Vehicles');
        $vehiclesRes->document('vehicleList')->set([
            'TrackHead' => 0,
            'Chassis40' => 0,
            'Chassis20' => 0,
            'Truck10W' => 0,
            'ClVan6' => 0,
            'ClVan4' => 0,
            'WingVan' => 0,
        ]);

        return redirect('/dashboard/drivers')->with('message',"Driver Account Created.");
    }

    public function staffStore( Request $request){
        // Function when staff creation form is submitted.

        $formFields = $request->validate([
            'fname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2'], 
            'lname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2' ], 
            'conum' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'email'],
            'dob' => ['required'],
            'ur_pass' => ['required','min:8','confirmed'],
            'ur_pass_confirmation' => ['required']
        ]);

        try{    
            // store necessary data for firebase auth user creation
            $user = [
                'email' => $formFields['email'],
                'emailVerified' => false,
                'password' => $formFields['ur_pass'],
                'Display Name' => $formFields['fname']." ".$formFields["lname"],
            ];
            // Create user in firebase auth with the data in $user
            $createdUser = $this->auth->createUser($user);

        } catch (Exception $e){
            return back()->with('errMsg', $e->getMessage());
        }
        
        // Send Email Verification
        $this->auth->sendEmailVerificationLink($formFields['email']);

        // Connect and add user info in database
        $db = $this->firestore->database();

        $colRef = $db->collection('Staffs');
        $colRef->document($createdUser->uid)->set([
            'Fname' => $formFields['fname'],
            'Lname' => $formFields['lname'],
            'Contact' => "0".$formFields['conum'],
            'Email' => $formFields['email'],
            'DOB' => $formFields['dob'],
            'Userlevel' => 'Staff',
            'addresses' => null,
        ]);

        return redirect('/dashboard/staffs')->with('message', 'Staff Account Created.');
    }

    public function tableRetrieve($colName, $toStore, $lastLog = false){

        $data['dis_ct'] = 0;
        $data['act_ct'] = 0;
        $data['ina_ct'] = 0;
        $data['tot_ct'] = 0;
        $data['dis_u'] = array();
        $data['act_u'] = array();
        $data['ina_u'] = array();

        $now = new \DateTime();
        $db = $this->firestore->database();
        $userDocs = $db->collection($colName)->orderBy('Fname')->documents();

        foreach($userDocs as $docu){
            $user = $this->auth->getUser($docu->id());
            $docdata = $docu->data();

            // If "User" has not logged in yet
            if($lastLog && ($user->metadata->lastLoginAt == null)){
                $datafields = [$user->uid,-1];
                foreach($toStore as $field){
                    $datafields = array_merge($datafields,array($docdata[$field]));
                }

                $data['ina_u'] = array_merge(
                    $data['ina_u'],
                    array( $datafields )
                );
                $data['ina_ct'] += 1;
                $data['tot_ct'] += 1;

            }else {
            
            $datafields= $lastLog ? [$user->uid, $user->metadata->lastLoginAt->diff($now)->days] : [$user->uid];

            foreach($toStore as $field){
                $datafields = array_merge($datafields,array($docdata[$field]));
            }


            // Check for disabled accounts
            if($user->disabled){      
                // add user data to the variable
                $data['dis_u'] = array_merge(
                    $data['dis_u'], 
                    array( $datafields )
                    );
                // Increment disabled users count.
                $data['dis_ct'] += 1;
            } else { 
                switch($colName){
                    case 'Users':
                        // Check if user loggin in within 30 days. Active users.
                        if($user->metadata->lastLoginAt->diff($now)->days < 30){
                            $data['act_u'] = array_merge(
                                $data['act_u'], 
                                array( $datafields )
                            );
                            $data['act_ct'] += 1;
                        } else { 
                            // Inactive users with more than 30 days since logged in.
                            $data['ina_u'] = array_merge(
                                $data['ina_u'],
                                array( $datafields )
                            );
                            $data['ina_ct'] += 1;
                        }
                        break;

                    case 'Drivers':
                        // Check if driver is available.
                        if($docdata['available']){
                            $data['ina_u'] = array_merge(
                                $data['ina_u'],
                                array( $datafields)
                            );
                            $data['ina_ct'] += 1;
                        } else{
                            $data['act_u'] = array_merge(
                                $data['act_u'],
                                array( $datafields)
                            );
                            $data['act_ct'] += 1;
                        }
                        break;

                    case 'Staffs':
                        $data['act_u'] = array_merge(
                            $data['act_u'],
                            array( $datafields)
                        );
                        $data['act_ct'] += 1;
                    break;

                    case 'Admins':
                        // Check if user loggin in within 30 days. Active users.
                        if($user->metadata->lastLoginAt->diff($now)->days < 30){
                            $data['act_u'] = array_merge(
                                $data['act_u'], 
                                array( $datafields )
                            );
                            $data['act_ct'] += 1;
                        } else { 
                            // Inactive users with more than 30 days since logged in.
                            $data['ina_u'] = array_merge(
                                $data['ina_u'],
                                array( $datafields )
                            );
                            $data['ina_ct'] += 1;
                        }
                        break;
                }
                }
                // Counter of total users.
                $data['tot_ct'] += 1;
            }
        }

        return $data;
    }

    public function quotePrices(){
        // function for Showing price list

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // $db = $this->firestore->database();
        // $priceRef = $db->collection('Prices');

        // // Get Pricing Documents
        // $docRef = $priceRef->document('ServiceType');
        // $gasRef = $priceRef->document('ServicePerKM');
        // $packRef = $priceRef->document('PackagingCost');
        // $chrgRef = $priceRef->document('Charges');
        // // Put their data in Variable.
        // $data['servType'] = $docRef->snapshot()->data();
        // $data['gas'] = $gasRef->snapshot()->data();
        // $data['pack'] = $packRef->snapshot()->data();
        // $data['charges'] = $chrgRef->snapshot()->data();
        
        return view('dashboard.price.prices');


    }

    public function showUpdPrices($type){
        // For showing UI when updating a price

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $priceRef = $db->collection('Prices');

        switch($type){
            case 'service':
                $docRef = $priceRef->document('ServiceType');
                $data['priceList'] = $docRef->snapshot()->data();
                break;
            case 'package':
                $docRef = $priceRef->document('PackagingCost');
                $data['priceList'] = $docRef->snapshot()->data();
                break;
            case 'gas':
                $docRef = $priceRef->document('ServicePerKM');
                $data['priceList'] = $docRef->snapshot()->data();
                break;
            case 'charges':
                $docRef = $priceRef->document('Charges');
                $data['priceList'] = $docRef->snapshot()->data();
                break;
        }   
        
        $data['category'] = $type;
        return view('dashboard.price.editPrice', $data);
    }

    public function savePrice(Request $request, $type){
        // For updating prices when form was submitted

        $formAns = $request->all();
        $rules = [];
        unset($formAns['_token']);

        foreach ($formAns as $code => $prc){
            $rules[$code] = [ 'required', 'numeric', 'gt:0' ];
        }

        $formFields = $request->validate($rules);
        // Validate input based on the Price type user wish to edit
        switch($type){
            case 'gas':
                $docName = 'ServicePerKM';
                break;
            case 'service':
                $docName = 'ServiceType';
                break;
            case 'package':
                $docName = 'PackagingCost';
                break;    
            case 'charges':
                $docName = 'Charges';
                foreach($formFields as $key => $value){
                    $formFields[$key] = $value/100.00;
                }
                break;
        }

        // Store update values to $upVal
        $upVal = array();
        foreach($formFields as $key => $value){
            array_push($upVal,['path'=> $key, 'value'=> $value]);
        }
        
        // Connect and update the database
        $db = $this->firestore->database();
        $docRef = $db->collection('Prices')->document($docName);
        $docRef->update($upVal);

        return redirect("/dash/prices")->with('message','Update Successful.');

    }

    public function addServType(Request $request){
        $formFields = $request->validate([
            'servType' => ['required', 'regex:/^[a-zA-Z -]+$/'],
            'servCode' => ['required', 'regex:/^[a-zA-Z0-9 .-]+$/'],
            'servChrg' => ['required', 'numeric', 'gt:0'],
            'servKM' => ['required','numeric', 'gt:0'],
            'servLg' => ['required','numeric', 'gt:0'],
            'servWd' => ['required','numeric', 'gt:0'],
            'servHg' => ['required','numeric', 'gt:0'],
            'servWeight' => ['required','numeric', 'gt:0'],
        ]);

        $addData = [$formFields['servCode'] => [
            'height' => (float) $formFields['servHg'],
            'length' => (float) $formFields['servLg'],
            'width' => (float) $formFields['servWd'],
            'maxVolume' => round( $formFields['servHg'] * $formFields['servLg'] * $formFields['servWd'] ,2),
            'name' => $formFields['servType'],
            'maxWeight' => (float) $formFields['servWeight'],
        ]];

        $db = $this->firestore->database();
        $servsRef = $db->collection('Vehicles')->document('ServiceTypes');
        $servCostRef = $db->collection('Prices')->document('ServiceType');
        $servKMRef = $db->collection('Prices')->document('ServicePerKM');

        $servsRef->set( $addData, ['merge' => true]);
        $servCostRef->set( [ $formFields['servCode'] => $formFields['servChrg'] ], ['merge' => true] );
        $servKMRef->set( [ $formFields['servCode'] => $formFields['servKM'] ], ['merge' => true] );

        return back()->with('message', $formFields['servType']." was successfully Created!");
    }

    public function addPackType(Request $request){

        $formFields = $request->validate([
            'pckType' => ['required', 'regex:/^[a-zA-Z -]+$/'],
            'pckCode' => ['required', 'regex:/^[a-zA-Z0-9 .-]+$/'],
            'pckCost' => ['required', 'numeric', 'gt:0'],
        ]);

        $db = $this->firestore->database();
        $packsRef = $db->collection('Vehicles')->document('PackTypes');
        $packCostRef = $db->collection('Prices')->document('PackagingCost');
        $packCoster = (int)$formFields['pckCost'];
        
        $packsRef->set( [ $formFields['pckCode'] => $formFields['pckType'] ], ['merge' => true]);
        $packCostRef->set( [ $formFields['pckCode'] => $packCoster ], ['merge' => true] );

        return back()->with('message', $formFields['pckType']." was successfully Created!");
    }

    public function dashProfile($ulevel, $uid ){
        // See User information when clicked in dashboard.
        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        // Check if User exists
        try{
            $user = $this->auth->getUser($uid);
        } catch (Exception $ex){
            return back()->with('errMsg', 'Account Not Found.');
        }
        
        // Get Info of a specific user from the database
        $db = $this->firestore->database();
        $docRef = $db->collection(ucfirst($ulevel))->document($uid);
        $docSnap = $docRef->snapshot();
        
        // Put user info into the $data variable
        $data = $docSnap->data();
        $data['uid'] = $docRef->id();
        $data['status'] = $user->disabled;

        switch($ulevel){
            case 'admins':
            case 'users':
            case 'staffs':

                return view('dashboard.accounts.userProfile', $data);

            case 'drivers':
                // Get Vehicle Frequencies
                $vehicles = $docRef->collection('Vehicles')->document('vehicleList')->snapshot()->data();
                $data['vehicles'] = $vehicles;

                // Get Ratings
                $ratingDocs = $docRef->collection('Ratings')->documents();
                $ratings = [];

                // Calculate Average Rating for each Item Category if driver has reviews
                if ( $ratingDocs->rows() ){
                    foreach($ratingDocs as $rateDoc){
                        $rateData = $rateDoc->data();
                        if(array_key_exists($rateData['category'], $ratings)){
                            array_push($ratings[$rateData['category']], $rateData['ratingAvg']);
                        }else{
                            $ratings[$rateData['category']] = [$rateData['ratingAvg']];
                        }
                    }

                    foreach($ratings as $key => $value){
                        // Get average rating for each goods category.
                        $ratings[$key] = array_sum($value)/count($value);
                    }
                }

                $data['ratings'] = $ratings;
                return view('dashboard.accounts.driverProfile', $data);

        }

    }

    public function userUpdate( Request $request, $ulev, $uid){
        // Updating user info in the dashboard

        $formFields = $request->validate([
            'Fname' => ['required', 'regex:/^[a-zA-Z -]+$/', 'min:2'], 
            'Lname' => ['required', 'regex:/^[a-zA-Z -]+$/', 'min:2' ], 
            'Contact' => ['required', 'numeric','digits:11'],
            'DOB' => ['required'],
        ]);

        // Store update values in $upVal
        $upVal = array();
        foreach($formFields as $key => $value){
            array_push($upVal,['path'=> $key, 'value'=> $value]);
        }

        // Connect and update the database
        $db = $this->firestore->database();
        $docRef = $db->collection(ucfirst($ulev))->document($uid);
        $docRef->update($upVal);
  
        return redirect("/dashboard/".$ulev."/$uid")->with('message','Update Successful.');

    }

    public function driverVehi (Request $request, $uid){
        // Function for Updating Driver's Vehicle Frequency & Vehicles can drive

        $formFields = $request->validate([
            'Chassis40' => ['required', 'numeric'],
            'WingVan' => ['required', 'numeric'],
            'TrackHead' => ['required', 'numeric'],
            'Chassis20' => ['required', 'numeric'],
            'ClVan4' => ['required', 'numeric'],
            'Truck10W' => ['required', 'numeric'],
            'ClVan6' => ['required', 'numeric'],
            'ableDrive' => [],
        ]);

        // Store update values to $upVal from the form.
        $freqData = $formFields;
        unset($freqData['ableDrive']);
        
        $upVal = array();
        foreach($freqData as $key => $value){
            array_push($upVal,['path'=> $key, 'value'=> (int)$value]);
        }
        $canUpVal = array();
        foreach($formFields['ableDrive'] as $key => $vals){
            array_push($canUpVal,['path'=> 'can_'.$key, 'value'=> $vals?true:false ]);
        }

        // Connect and update to database
        $db = $this->firestore->database();
        $drivDoc = $db->collection("Drivers")->document($uid);
        $freqDocRef = $drivDoc->collection('Vehicles')->document('vehicleList');
        $drivDoc->update($canUpVal);
        $freqDocRef->update($upVal);

        return redirect("/dashboard/drivers/".$uid)->with('message','Update Successful.');

    }

    public function driverAvailUpd (Request $request){
        $fields = $request->all();
        unset($fields['_token']);

        $success = 0;
        $db = $this->firestore->database();
        $drivRef = $db->collection('Drivers');

        if( array_key_exists('toUnav', $fields) ){
            foreach($fields['toUnav'] as $driver){
                $drivDoc = $drivRef->document($driver);
                $drivDoc->update([
                    ['path' => 'available', 'value' => false],
                ]);
                $success += 1;
            }
        }

        if ( array_key_exists('toAvail', $fields) ){
            foreach($fields['toAvail'] as $driver){
                $drivDoc = $drivRef->document($driver);
                $drivDoc->update([
                    ['path' => 'available', 'value' => true],
                ]);
                $success += 1;
            }
        }

        return back()->with('message', "$success Driver/s Updated!");
    }

    public function disableUser($ulev, $uid){
        // Deactivate a user
        $updatedUser = $this->auth->disableUser($uid);

        return redirect('/dashboard/'.$ulev)->with('message','User Deativated Succesfully.');
        
    }

    public function enableUser($ulev, $uid){
        // Activate a user
        $updatedUser = $this->auth->enableUser($uid);

        return redirect('/dashboard/'.$ulev)->with('message','User Was Enabled Succesfully.');
    }

    
}
