<?php

namespace App\Http\Controllers;

use DatePeriod;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Curl;
class ReportCtrl extends Controller
{
    private $factory;
    private $firestore;

    public function __construct(){
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->firestore = $this->factory->createFirestore();
    }

    public function showVehicles(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $vehicleRef = $db->collection('Vehicles');

        $vehiType = request('type') ?? 'ClVan4'; 


        $data['AvVehicles'] = [];
        $data['UnVehicles'] = [];
        $data['servTyp'] = $vehiType;

        // Returns newest -> oldest -> null date arragement
        $vehiDocs = $vehicleRef->document('List')->collection($vehiType)
                        ->orderBy('last_used', 'DESC');

        foreach($vehiDocs->documents() as $vehicle){
            $vehiData = $vehicle->data();
            $vehiData['vehicleID'] = $vehicle->id();

            if($vehiData['available']){
                $data['AvVehicles'] = array_merge(
                    $data['AvVehicles'], 
                    [$vehiData] 
                    );
            } else {
                $data['UnVehicles'] = array_merge(
                    $data['UnVehicles'], 
                    [$vehiData] 
                    );
            }

        }

        // Get Vehi For Choices
        $nameDocs = $vehicleRef->document('ServiceTypes')->snapshot();
        $data['vehiNames'] = $nameDocs->data();
        //dd($data);
        return view('reports.vehicles', $data);
    }

    public function addVehicle( Request $request){
        $formFields = $request->validate([
            'vehiType' => ['required'],
            'plate' => ['required','regex:/^([a-zA-Z]{3})([0-9]{4})$/', 'size:7'],
        ]);

        $createVal = [
            'last_used' => null,
            'available' => true,
        ];

        $capPlate = strtoupper($formFields['plate']);

        $db = $this->firestore->database();
        $vehiRef = $db->collection('Vehicles')->document('List')->collection($formFields['vehiType']);

        $vehiRef->document($capPlate)->set($createVal);

        return back()->with('message', "$capPlate Successfully Added!");
    }

    public function updateAvail(Request $request, $servTyp){
        $fields = $request->all();
        unset($fields['_token']);

        $success = 0;
        $db = $this->firestore->database();
        $vehiRef = $db->collection('Vehicles')->document('List')->collection($servTyp);

        // For updating vehicles to unavailable
        if( array_key_exists('toUnav', $fields) ){
            foreach($fields['toUnav'] as $vehicle){
                $vehiDoc = $vehiRef->document($vehicle);
                $vehiDoc->update([
                    ['path' => 'available', 'value' => false ],
                ]);
                $success += 1;
            }
        }

        // For updating vehicles to Available
        if( array_key_exists('toAvail', $fields) ){
            foreach($fields['toAvail'] as $vehicle){
                $vehiDoc = $vehiRef->document($vehicle);
                $vehiDoc->update([
                    ['path' => 'available', 'value' => true ],
                ]);
                $success += 1;
            }
        }

        return back()->with('message', "$success Vehicle/s Updated!");
    }

    public function showSales(){

        if(!session()->has('uid') || session('user')['Userlevel'] != 'Admin'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $salesRef = $db->collection('Sales');

        $week = request('week') ?? 'Week1';
        $month = request('month') ?? date('F');
        $year = request('year') ?? date('Y');

        if($week != 'null'){

            $weekly = [];

            $saleDoc = $salesRef->document($year)->collection($month)->document($week);

            if( $saleDoc->snapshot()->exists()){

                $weekNum = $saleDoc->snapshot()->data()['week'];
                $mydate = new \DateTime(); 
    
                for($i = 1; $i <= 7; $i++){
                    $dayStr = $mydate->setISODate(intval($year), $weekNum, $i)->format('m-d');
                    $date = (\DateTime::createFromFormat('m-d', $dayStr))->format('F d');

                    if(!str_starts_with($date, $month) || $date > date('F d') ){
                        continue;
                    }

                    $day = $saleDoc->collection($dayStr)->documents();
    
                    $dayEarn = [];
                    if(count($day->rows()) == 0){
                        $weekly[$date] = 0;
                    } else {
                        foreach( $day as $dateDoc){
                            $dayEarn[] = $dateDoc->data()['amount'];
                        }
                        $weekly[$date] = array_sum($dayEarn);
                    }
                }
            } else {
                return back()->with('errMsg', 'No Data Found.');
            }

        }

        // For select year option
        $yearOpt = [];

        foreach ($salesRef->documents() as $doc){
            $yearOpt[] = $doc->id();
        }


        $data['yearOpt'] = $yearOpt;
        $data['year'] = $year;
        $data['week'] = $week;
        $data['month'] = $month;
        $data['weekly'] = $weekly;
        return view('reports.sales', $data);
    }

    public function showDriverRating(){

        if(!session()->has('uid') || session('user')['Userlevel'] == 'User'){
            return redirect('/');
        } elseif (!session('verified')){
            return redirect('/verifyAccount');
        }

        $db = $this->firestore->database();
        $driverRef = $db->collection('Drivers');

        $drivAndRates = [];
        $driverDocs = $driverRef->select(['Fname', 'Lname'])->documents();
        foreach($driverDocs as $driver){
            $rateDoc = $driverRef->document($driver->id())->collection('Ratings')->documents();
            
            if(count($rateDoc->rows()) == 0){
                continue;
            }
            $drivName = $driver->data();
            $name = $drivName['Fname']." ".$drivName['Lname'];

            $rate['ratingAvg'] = [];
            $rate['timelinessRating'] = [];
            $rate['handlingRating'] = [];
            $rate['professionalismRating'] = [];
            $rate['driverID'] = $driver->id();
            foreach($rateDoc as $rating){
                $rateData = $rating->data();
                $rate['ratingAvg'][] = $rateData['ratingAvg'];
                $rate['timelinessRating'][] = $rateData['timelinessRating'];
                $rate['handlingRating'][] = $rateData['handlingRating'];
                $rate['professionalismRating'][] = $rateData['professionalismRating'];
            }

            // Get average of all ratings for each type
            $rate['ratingAvg'] = array_sum($rate['ratingAvg']) / count($rate['ratingAvg']);
            $rate['timelinessRating'] = array_sum($rate['timelinessRating']) / count($rate['timelinessRating']);
            $rate['handlingRating'] = array_sum($rate['handlingRating']) / count($rate['handlingRating']);
            $rate['professionalismRating'] = array_sum($rate['professionalismRating']) / count($rate['professionalismRating']);

            // Put results in array
            $drivAndRates[$name] = $rate;
        }

        $data['driverRate'] = $this->quickSort($drivAndRates);
        //$data['top3'] = array_slice($this->quickSort($drivAndRates),0,3);

        return view('reports.driverRate', $data);


    }

    public function quickSort($the_array){
        $less = $greaterOrEq = [];

        if(count($the_array) < 2){
            return $the_array;
        }

        $pivot_key = key($the_array);
        $pivot = array_shift($the_array);

        // Compare and arrange drivers in DESCENDING order
        foreach($the_array as $key => $value){

            if( $value['ratingAvg'] <= $pivot['ratingAvg'] ){
                $greaterOrEq[$key] = $value;
            } else {
                $less[$key] = $value;
            }
        }
        return array_merge($this->quickSort($less), [$pivot_key => $pivot], $this->quickSort($greaterOrEq));
    }

    public function dummyFunc(){

        $client = new \GuzzleHttp\Client();
        //dd(session()->all());
        $responser = $client->request('POST', 'https://api.paymongo.com/v1/checkout_sessions', [
            'body' => '{"data":{"attributes":{"billing":{"name":"'.session('user')['Fname'].' '.session('user')['Lname'].'" ,"email":"john@gmail.com"},"line_items":[{"currency":"PHP","amount":15720,"description":"Some Desc","name":"Some Product Name","quantity":1}],"payment_method_types":["card","gcash","dob"],"billing":{"address":{"line1":"street", "city":"city","country":"PH","state":"province", "postal_code":"175"},"email":"'.session('user')['Email'].'", "name":"'.session('user')['Fname'].' '.session('user')['Lname'].'" }, "send_email_receipt":true, "show_description":true, "show_line_items":true, "description":"Checkout Desc", "success_url":"http://127.0.0.1:8000"}}}',

            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'authorization' => 'Basic '.env('AUTH_PAY')
            ],
 
        ]);

        $xd = json_decode($responser->getBody());
        dd($xd->data->attributes->checkout_url);
      
       
    }
}
