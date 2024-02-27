<?php

namespace App\Http\Controllers;

use App\Models\AllInstitutions;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstitutionController extends Controller
{
    //
   public function saveAllInstitutions(Request $request){
    $validator = Validator::make($request->all(), [
        'institutions' => 'required|array',
        'institutions.*' => 'required|string', // Assuming 'institutions' is an array of strings
    ]);

    // If validation fails, return error response
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Store each institution in the database
    foreach ($request->institutions as $institutionName) {
        // You may need to adjust this based on your Institution model
        AllInstitutions::create([
            'name' => $institutionName,
            // Add other fields if needed
        ]);
    }

    // Return success response
    return response()->json(['message' => 'Institutions added successfully'], 201);

   } 


    public function index()
    {
        $institutions = Institution::all();

        return response()->json(['institutions' => $institutions]);
    }
}
