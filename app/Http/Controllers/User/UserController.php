<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\EnterUserInfoRequest;
use App\Http\Requests\User\RegistrationPhoneRequest;
use App\Http\Requests\User\ValidationPhoneRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $message=['message'=>'ok'];
        return response()->json($message);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isJson()) {
            $processedRequest = new Request($request->json()->all());
        } else {
            $processedRequest = $request;
        }
        return $this->dataProcessor($processedRequest);
    }
    public function registratePhone(RegistrationPhoneRequest $request)
    {
        $phone = $request->input('phone');
        $otpCode = rand(1, 9999);
        $userData = ['phone' => $phone, 'otp_code' => $otpCode];
        $user = User::create($userData);

        return response()->json([
            'message' => 'User created, OTP generated',
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'otp_code' => $user->otp_code,
            ]
        ], 201);
    }
    public function validatePhone(ValidationPhoneRequest $request)
    {
        $identName = $request->filled('id') ? 'id' : 'phone';
        $identValue = $request->input($identName);

        $user = User::where($identName, $identValue)->first();

        if ($user) {
            if ($request->input('otp_code') === $user->otp_code) {
                $user->update(['phone_verified_at' => now()]);
                return response()->json([
                    'user' => $user,
                    'message' => 'Phone verified successfully'
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Incorrect code'
                ], 400);
            }
        }

        return response()->json([
            'message' => 'User not found'
        ], 404);
    }
    public function enterUserInfo(EnterUserInfoRequest $request)
    {
        $identifier = $request->filled('id') ? 'id' : 'phone';
        $user = User::where($identifier, $request->input($identifier))->first();

        if (!$user) {
            return response()->json(['message' => 'User does not exist'], 404);
        }

        if (!$user->phone_verified_at) {
            return response()->json(['message' => 'You must verify your phone by OTP code'], 400);
        }
        $userData=[
            'first_name'=>$request->input('first_name'),
            'second_name'=>$request->input('second_name'),
            ];
        $user->update($userData);

        return response()->json(['user' => $user, 'message' => 'User information updated successfully'], 200);

        /*$processedRequest = $request->isJson() ? new Request($request->json()->all()) : $request;
        if (!empty($processedRequest->input('id'))) {
            $identName = 'id';
            $identValue = $processedRequest->input('id');
        } else if (!empty($processedRequest->input('phone'))) {
            $identName = 'phone';
            $identValue = $processedRequest->input('phone');
        }
        if (!empty(User::where($identName, $identValue)->first())) {
            $user = User::where($identName, $identValue)->first();
            if (!empty($user->phone_verified_at)) {
                if (!empty($request->input('first_name'))) {
                    $firstName = $request->input('first_name');
                    $secondName = $request->input('second_name');
                    //$role=$request->input('role'); //дефолт 'user' але не знаю вимоги створення адміна через постман
                    // чи в самій бд чи через інший роут і тд.
                    if (isset($secondName)) {
                        $user->update([
                            'first_name' => $firstName,
                            'second_name' => $secondName,
                        ]);
                        return response()->json([
                            'user' => $user
                        ], 201);
                    }
                }
            } else {
                return response()->json(
                    ['message' => 'you must verificate by otp code'], 400
                );
            }
        } else {
            return response()->json(
                ['message' => 'user didnt exist'], 400
            );
        }*/
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy(['id'=>$id]);
    }

}