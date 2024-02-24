<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use App\Models\Programme;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{
    //
    public function createInstitutionWithProgrammes(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'institution_name' => 'required|string',
        'programmes' => 'required|array',
        'programmes.*.name' => 'required|string',
        'programmes.*.yearGrantedInterimOrAccredition' => 'required|integer',
        'programmes.*.yearApproved' => 'required|integer',
        'programmes.*.accreditationStatus' => 'required|string',
        'programmes.*.approvedStream' => 'required|integer',
        'programmes.*.faculty' => 'required|string',
        'programmes.*.expirationDate' => 'required|date',
        'programmes.*.isTechnologyBased' => 'required|boolean', // Add this rule
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
    // // Create the institution
    // $institution = Institution::create([
    //     'name' => $validatedData['institution_name'],
    //     // Add other institution attributes here
    // ]);

    // Create programmes for the institution
    foreach ($validatedData['programmes'] as $programmeData) {
        // Calculate numberOfStudents based on isTechnologyBased
        // $numberOfStudents = $programmeData['approvedStream'] * ($programmeData['isTechnologyBased'] ? 40 : 60);

        $programme = new Programme([
            'name' => $programmeData['name'],
            'yearGrantedInterimOrAccredition' => $programmeData['yearGrantedInterimOrAccredition'],
            'yearApproved' => $programmeData['yearApproved'],
            'accreditationStatus' => $programmeData['accreditationStatus'],
            'approvedStream' => $programmeData['approvedStream'],
            'faculty' => $programmeData['faculty'],
            'expirationDate' => $programmeData['expirationDate'],
            'isTechnologyBased' => $programmeData['isTechnologyBased'], // Add isTechnologyBased
            // 'numberOfStudents' => $numberOfStudents, // Add numberOfStudents
            // Add other programme attributes here
        ]);

        // Associate the programme with the institution
        $institution->programmes()->save($programme);
    }

    return response()->json(['message' => 'Institution and programmes created successfully'], 201);
}
}
