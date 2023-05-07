<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function saveToken(Request $request)
    {
        $validatedData =  $request->validate([
            'phone' => 'required|unique:users|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]); 

        $user = User::create($validatedData);
        return response()->json(['token saved successfully.']);

      }

      public function UpdateRecord(Request $request)
    {

        User::where("phone", $request->phone)->update(["phoneverified" => 1]);
        return response()->json(['token saved successfully.']);

      }

    
}
