<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test' , function(){
    return 'Hello World';
});

Route::post('/sanctum/token', function (Request $request) {


    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'deviceName' => 'required',
    ]);

//    return response($request);


    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->deviceName)->plainTextToken;
});
