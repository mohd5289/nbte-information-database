<?php

namespace App\Http\Controllers;

use App\Models\Allprogrammes;
use App\Models\Institution;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammesController extends Controller
{
  
   public function addAllProgrammes(Request $request){
    $validator = Validator::make($request->all(), [
        'programs' => 'required|array',
        'programs.*.name' => 'required|string',
        'programs.*.isTechnologyBased' => 'required|string',
        'programs.*.faculty' => 'required|string',
    ]);

    // If validation fails, return error response
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }

    // Store each programme in the database
    foreach ($request->programs as $programData) {
        // You may need to adjust this based on your Programme model
        Allprogrammes::create([
            'name' => $programData['name'],
            'isTechnologyBased' => $programData['isTechnologyBased'],
            'faculty' => $programData['faculty'],
            // Add other fields if needed
        ]);
    }

    // Return success response
    return response()->json(['message' => 'Programmes added successfully'], 201);
   } 



public function getAllProgrammes(){

    $programs = Allprogrammes::all();

    // Return the programs as a JSON response
    return response()->json(['programs' => $programs], 200);
}



public function createInstitutionWithProgrammes(Request $request){
    $validatedData = $request->validate([
        'institution_name' => 'required|string',
        'programmes' => 'required|array',
        'programmes.*.name' => 'required|string',
        'programmes.*.yearGrantedInterimOrAccredition' => 'required|integer',
        'programmes.*.yearApproved' => 'required|integer',
        'programmes.*.accreditationStatus' => 'required|string',
        'programmes.*.approvedStream' => 'required|integer',
        // Add this rule
        // Add other validation rules as needed
    ]);

    $existingInstitution = Institution::where('name', $validatedData['institution_name'])->first();

    if ($existingInstitution) {
        // If institution with the same name exists, associate programmes with the existing institution
        $institution = $existingInstitution;
    } else {
        // If institution with the same name doesn't exist, create a new one
        $institution = Institution::create([
            'name' => $validatedData['institution_name'],
            // Add other institution attributes here
        ]);
    }
    foreach ($validatedData['programmes'] as $programmeData) {
        // Calculate numberOfStudents based on isTechnologyBased
        // $numberOfStudents = $programmeData['approvedStream'] * ($programmeData['isTechnologyBased'] ? 40 : 60);
        $expirationDate = null;
        switch ($programmeData['accreditationStatus']) {
            case 'Accredited':
                $expirationDate = date('Y-m-d', strtotime('+5 years', strtotime($programmeData['yearGrantedInterimOrAccredition'] . '-10-01')));
                break;
            case 'Interim':
                $expirationDate = date('Y-m-d', strtotime('+1 year', strtotime($programmeData['yearGrantedInterimOrAccredition'] . '-10-01')));
                break;
            case 'Approved':
                $expirationDate = date('Y-m-d', strtotime('+2 years', strtotime($programmeData['yearGrantedInterimOrAccredition'] . '-10-01')));
                break;
            // Add more cases if needed for other accreditation statuses
        }
    
        $programme = new Programme([
            'name' => $programmeData['name'],
            'yearGrantedInterimOrAccredition' => $programmeData['yearGrantedInterimOrAccredition'],
            'yearApproved' => $programmeData['yearApproved'],
            'accreditationStatus' => $programmeData['accreditationStatus'],
            'approvedStream' => $programmeData['approvedStream'],
            'expirationDate' => $expirationDate,
             // Add isTechnologyBased
            // 'numberOfStudents' => $numberOfStudents, // Add numberOfStudents
            // Add other programme attributes here
        ]);

        // Associate the programme with the institution
        $institution->programmes()->save($programme);
    }
    return response()->json(['message' => 'Institution and programmes created successfully'], 201);
}
     
//  public function createInstitutionWithProgrammes(Request $request){
//     // Validate incoming request data
   
// }
}
