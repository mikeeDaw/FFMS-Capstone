<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;


class QuoteCtrl extends Controller
{
    private $factory;
    private $firestore;

    public function __construct()
    {
        $this->factory = (new Factory)
        ->withServiceAccount(env('FIRE_CRED'))
        ->withDatabaseUri('https://lebriafms-default-rtdb.firebaseio.com/');

        $this->firestore = $this->factory->createFirestore();
    }

    public function index(){
        if(session('total') != Null){
            return view('quote.quotation');
        }else{
        return view('quote.quotation');
        }
    }

    public function calcQuote(Request $request){
        // Added: Form Validation, Select Fields, @error
        $formfields = $request->validate([
            'city' => ['required'],
            'province' => ['required'],
            'barang' => ['required'],
            'zipcode'=> ['required'],
            'weight'=> ['required','numeric', 'gt:0'],
            'length'=> ['nullable','numeric', 'gt:0'],
            'width'=> ['nullable','numeric', 'gt:0'],
            'height'=> ['nullable','numeric', 'gt:0'],
            'service'=> ['required'],
            'package'=> ['required'],
            'value'=> ['required','numeric', 'gt:0'],
            'qty'=> ['required','numeric', 'gt:0'],
            'distance' => [],
        ]);

        if($formfields['distance'] != 0 ){

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
            $data['freightChrg'] = $servType[$formfields['service']] + $pack[$formfields['package']] + ($formfields['qty'] * $formfields['weight']) + ($formfields['distance'] * $gas[$formfields['service']]);

            // IC = Insurance Percent * Declared Value
            $data['insurFee'] = $charges['Insurance'] * $formfields['value'];
            
            // SC = Service Type * Service Charge Percentage
            $data['servChrg'] = $servType[$formfields['service']] * $charges['ServiceChg'];
            
            // Sub = FC + IC + SC
            $data['subtotal'] = $data['freightChrg'] + $data['insurFee'] + $data['servChrg'];

            // Total = Sub + ( Sub * Cost Percentage )
            $data['total'] = $data['subtotal'] + ($data['subtotal'] * $charges['TotalCost']);

            return redirect('/quote')->with($data)->withInput();

        } else {

            return redirect()->back()->withErrors(['addrErr' => 'Address not Found.'])->withInput();
        }
    }
}
