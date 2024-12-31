<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HandleTokenController extends Controller
{
    //
    public function handleToken(Request $request)
    {



        $userfilled =  $request->validate([
            'email' => 'required',
            'token' => 'required',

        ]);


        $checkUserNote =  DB::table('token')
            ->where('email', $request['email'])
            ->get();

        if (sizeof($checkUserNote) == 0) {
            DB::table('token')->insert([
                'email' => $request['email'],
                'token_value' => $request['token']
            ]);
        } else {
            DB::table('token')
                ->where('email',    $request['email'])
                ->update([
                    'email' => $request['email'],
                    'token_value' => $request['token'],
                ]);
        }

        $response = [
            'statut'  => 'OK',
            'notes'  => $checkUserNote,
        ];

        return response(
            $response,
            200
        );
    }

    public function getUserToken(Request $request)
    {
        $userfilled =  $request->validate([
            'email' => 'required',

        ]);


        $checkUserNote =  DB::table('token')
            ->where('email', $request['email'])
            ->get();


        $response = [
            'statut'  => 'OK',
            'notes'  => $checkUserNote,
        ];

        return response(
            $response,
            200
        );
    }
}
