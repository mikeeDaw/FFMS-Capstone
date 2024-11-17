<?php

namespace App\Http\Controllers;

use Exception;
use Kreait\Firebase\Factory;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;
use Google\Cloud\Firestore\FirestoreClient;

class LogCtrl extends Controller
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

    public function createRegistration(){

        // Redirect to Home if a user is logged in.
        if(session()->has('uid')){
            return redirect('/');
        }

        return view('logforms.register');
    }

    public function storeRegistration(Request $request){
        // Validate form inputs.
        $formFields = $request->validate([
            'fname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2'], 
            'lname' => ['required', 'regex:/^[a-zA-Z \'-]+$/', 'min:2' ], 
            'conum' => ['required', 'numeric','digits:10'],
            'email' => ['required', 'email'],
            'dob' => ['required'],
            'ur_pass' => ['required','min:8','confirmed'],
            'ur_pass_confirmation' => ['required'],
            'dataCheck' => ['required'],
        ]);

        dd($formFields);
        try{

            $user = [
                'email' => $formFields['email'],
                'emailVerified' => false,
                'password' => $formFields['ur_pass'],
                'Display Name' => $formFields['fname']." ".$formFields["lname"],
            ];
    
            // Create the user.
            $createdUser = $this->auth->createUser($user);

        } catch (Exception $e){
            return back()->with('errMsg', $e->getMessage())->withInput();
        }
        

        $user = $this->auth->getUserByEmail($formFields['email']);

        // Store info to 'User' Collection.
        $db = $this->firestore->database();

        $colRef = $db->collection('Users');
        $colRef->document($user->uid)->set([
            'Fname' => $formFields['fname'],
            'Lname' => $formFields['lname'],
            'Contact' => "0".$formFields['conum'],
            'Email' => $formFields['email'],
            'DOB' => $formFields['dob'],
            'Userlevel' => 'User',
            'addresses' => null,
        ]);
        
        // Send Email Verification
        $this->auth->sendEmailVerificationLink($formFields['email']);

        $signInResult = $this->auth->signInWithEmailAndPassword($formFields['email'], $formFields['ur_pass']);

        $docu = $colRef->document($user->uid);
        $docData = $docu->snapshot()->data();
        
        $request->session()->put('uid',$user->uid);
        $request->session()->put('verified', $user->emailVerified);
        $request->session()->put('user', $docData);

        return redirect('/verifyAccount')->with('message', " Welcome! Please Verify your Account to Continue.");

    }

    public function showLogin(){

        // Redirect to Home if a user is logged in.
        if(session()->has('uid')){
            return redirect('/');
        }
        else{
            return view('logforms.loginform');
        }

    }

    public function verifyLogin(Request $request){

        $db = $this->firestore->database();

        // Validate login form inputs.
        $fields = $request->validate([
            'email' => ['required','email'],
            'pass' => ['required']
        ]);

        try {
            // Match email in firebase users list
            $user = $this->auth->getUserByEmail($fields['email']);

            try{
                // Match password of specific user
                $signInResult = $this->auth->signInWithEmailAndPassword($fields['email'], $fields['pass']);
                
                $userLevel = ($db->collection('Admins')->document($user->uid)->snapshot()->exists()) ?               
                "Admins" : (
                    ($db->collection('Staffs')->document($user->uid)->snapshot()->exists()) ? 
                        "Staffs" : (
                            ($db->collection('Users')->document($user->uid)->snapshot()->exists()) ?
                            "Users" : "Drivers"
                        ) 
                );

                if($userLevel == 'Drivers'){
                    return redirect('/')->with('errMsg', 'For Drivers, Please Use the Mobile Application.');
                }

                $docRef = $db->collection($userLevel)->document($user->uid);
                $docData = $docRef->snapshot()->data();
                
                /* gawa siguro ng error handling sa pagkuha ng data */
                
                // Store user data in session or pass it with routing.
                $request->session()->put('uid',$user->uid);
                $request->session()->put('verified', $user->emailVerified);
                $request->session()->put('user', $docData);           

                if(!$user->emailVerified){
                    return redirect('/verifyAccount');
                } else {

                    switch($docData['Userlevel']){
                        case 'User':
                            return redirect()->route('home');
                        default:
                            return redirect('/dashboard');
                    }
                }

     
            } catch(Exception $ex){
                // This catch is for wrong password.
                return back()->withErrors(['passErr' => 'Incorrect Password'])->withInput();
            }


        } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
            // This catch is for invalid email address.
            return back()->withErrors(['emailErr' => 'Incorrect Email'])->withInput();
        }
    }

    public function verifyAcc(){
        $user = session('user');
        $authUser = $this->auth->getUserByEmail($user['Email']);
        
        if($authUser->emailVerified){
            session()->forget(['verified']);
            session(['verified' => $authUser->emailVerified]);

            return redirect('/');
        } else {

            return view('logforms.verifyAccount');
        }
    }

    public function showForgot(){
        return view('logforms.forgotPass');
    }

    public function sendForgot(Request $request){

        $formFields = $request->validate([
            'email' => ['required', 'email'],
        ]);

        try{
            $this->auth->sendPasswordResetLink($formFields['email']);
        } catch (Exception $ex){
            return back()->with('errMsg', 'Email Provided is Not Registered.');
        }


        return back()->with('message', 'Password Reset Link Sent to Email.');

    }

    public function logout(){
        
        session()->flush();
        return redirect('/')->with('message',"You have been logged out.");
    }
    
}
