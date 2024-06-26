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

   public function getAllInstitutions(){

    $institutions = AllInstitutions::all();

    // Return the programs as a JSON response
    return response()->json(['institutions' => $institutions], 200);
}

public function updateInstitutionDetails(Request $request)
   {
       // Define validation rules array
       $rules = [
           'name' => 'required|string', // Assuming 'name' is the institution name
           'address' => 'required|string',
           'year_established' => 'required|integer',
           'ownership' => 'required|string'
       ];
   
    //    Check the ownership type
       if ($request->ownership === 'Private') {
           // If ownership is private, add validation rules for proprietor and rector fields
           $rules['proprietor_name'] = 'nullable|string';
           $rules['proprietor_email'] = 'nullable|email';
           $rules['proprietor_phone'] = 'nullable|string';
           $rules['rector_name'] = 'nullable|string';
           $rules['rector_email'] = 'nullable|email';
           $rules['rector_phone'] = 'nullable|string';
           // Remove validation rules for registrar fields
           unset($rules['registrar_name'], $rules['registrar_email'], $rules['registrar_phone']);
       } else {
           // If ownership is federal or state, add validation rules for rector and registrar fields
           $rules['rector_name'] = 'nullable|string';
           $rules['rector_email'] = 'nullable|email';
           $rules['rector_phone'] = 'nullable|string';
           $rules['registrar_name'] = 'nullable|string';
           $rules['registrar_email'] = 'nullable|email';
           $rules['registrar_phone'] = 'nullable|string';
           // Remove validation rules for proprietor fields
           unset($rules['proprietor_name'], $rules['proprietor_email'], $rules['proprietor_phone']);
       }
   
       // Validate the request data
       $validator = Validator::make($request->all(), $rules);
   
       // If validation fails, return error response
       if ($validator->fails()) {
           return response()->json(['errors' => $validator->errors()], 400);
       }
   
       // Find the institution by name
       $institution = AllInstitutions::where('name', $request->name)->first();
   
       // If the institution doesn't exist, return error response
       if (!$institution) {
           return response()->json(['error' => 'Institution not found'], 404);
       }
   
       // Update the institution details
       $institution->update($request->all());
   
       // Return success response
       return response()->json(['message' => 'Institution details updated successfully'], 200);
   }
    public function index()
    {
        $institutions = Institution::all();

        return response()->json(['institutions' => $institutions]);
    }
}
