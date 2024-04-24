<?php

namespace App\Http\Controllers;

use App\Models\Allprogrammes;
use App\Models\Institution;
use App\Models\Programme;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgrammesController extends Controller
{
    public function getAllInstitutionsAndProgrammes(Request $request)
    {
        $institutionsWithPrograms = Institution::with('programmes')->get();
        $query = Institution::query();

        // Filter by program name if provided
        if ($request->has('programme_name_contains')) {
            $query->whereHas('programmes', function ($subquery) use ($request) {
                $subquery->where('name', 'like', '%' . $request->input('programme_name_contains') . '%');
            });
        }
    
        // Filter by program name that starts with a specific string
        if ($request->has('programme_name_starts_with')&& $request->input('programme_name_starts_with') !=='none') {
            $query->whereHas('programmes', function ($subquery) use ($request) {
                $subquery->where('name', 'like', $request->input('programme_name_starts_with') . '%');
            });
        }
    
        // Filter by accreditation status
        if ($request->has('accreditation_status') && $request->input('accreditation_status') !== 'all') {
            $query->whereHas('programmes', function ($subquery)  use ($request) {
                $subquery->where('accreditationStatus', $request->input('accreditation_status'));
            });
        }
    
        // Filter by number of streams
        if ($request->has('streams') && $request->input('streams') !== 'any') {
            $query->whereHas('programmes', function ($subquery) use ($request) {
                $subquery->where('approvedStream', $request->input('streams'));
            });
        }
        $institutionsWithPrograms = $query->get();
        $data = [];

        foreach ($institutionsWithPrograms as $institution) {
            $data[] = [
                'institution_name' => $institution->name,
                'programmes' => $institution->programmes->toArray(),
            ];
        }

        return response()->json(['data' => $data]);
    }
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

public function updateInstitutionAndProgrammes(Request $request)
{
    // Validate the incoming request
    $validatedData = $request->validate([
        'institution_name' => 'required|string',
        'programmes' => 'required|array',
        'programmes.*.name' => 'required|string',
        'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
        'programmes.*.yearApproved' => 'required|integer',
        'programmes.*.approvedStream' => 'required|integer|min:0',
        'programmes.*.expirationDate' => 'required|date',
        // Add other validation rules as needed
    ]);

    // Find the institution by its name
    $institution = Institution::where('name', $validatedData['institution_name'])->first();

    if (!$institution) {
        return response()->json(['message' => 'Institution not found'], 404);
    }

    // Iterate over each program
    foreach ($validatedData['programmes'] as $programData) {
        // Find the program by its name under the given institution
        $program = $institution->programmes()->where('name', $programData['name'])->first();

        // Calculate the gap in years
        $expirationDate = date('Y-m-d', strtotime($programData['expirationDate']));
        $yearExpiration = date('Y', strtotime($expirationDate));
        $yearGranted = $programData['yearGrantedInterimOrAccreditation'];
        $gap = $yearExpiration - $yearGranted;

        // Determine accreditationStatus based on the gap
        if ($expirationDate < strtotime('now')) {
            $accreditationStatus = Status::EXPIRED;
        } elseif ($gap == 5) {
            $accreditationStatus = Status::ACCREDITED;
        } elseif ($gap == 2) {
            $accreditationStatus = Status::APPROVED;
        } elseif ($gap == 1) {
            $accreditationStatus = Status::INTERIM;
        } else {
            return response()->json(['error' => 'Invalid gap between expirationDate and yearGrantedInterimOrAccreditation'], 400);
        }

        if ($program) {
            // Update the program with the validated data
            $program->fill([
                'yearGrantedInterimOrAccreditation' => $programData['yearGrantedInterimOrAccreditation'],
                'yearApproved' => $programData['yearApproved'],
                'approvedStream' => $programData['approvedStream'],
                'expirationDate' => $expirationDate,
                'accreditationStatus' => $accreditationStatus,
                // Add other program attributes here
            ]);
            $program->save();
        } else {
            return response()->json(['error' => 'Programme not found in the institution'], 400);
        }
    }

    return response()->json(['message' => 'Programs updated successfully']);
}


public function createInstitutionWithProgrammes(Request $request){
    $validatedData = $request->validate([
        'institution_name' => 'required|string',
        'programmes' => 'required|array',
        'programmes.*.name' => 'required|string',
        'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
        'programmes.*.yearApproved' => 'required|integer',
        'programmes.*.approvedStream' => 'required|integer|min:0',
        // Add this rule
        'programmes.*.expirationDate' => 'required|date',
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
    $submittedProgrammeNames = collect($validatedData['programmes'])->pluck('name')->toArray();
    if (count(array_unique($submittedProgrammeNames)) !== count($submittedProgrammeNames)) {
        return response()->json(['error' => 'Duplicate programme names are not allowed in the submitted data'], 400);
    }

    $uniqueProgramNames = [];
    foreach ($validatedData['programmes'] as $programmeData) {
        if (in_array($programmeData['name'], $uniqueProgramNames)) {
            return response()->json(['error' => 'Program with the same name already exists in this institution'], 400);
        }
        if ($institution->programmes()->where('name', $programmeData['name'])->exists()) {
            return response()->json(['error' => 'Program with the same name already exists in this institution'], 400);
        }
        $uniqueProgramNames[] = $programmeData['name'];
        // Calculate numberOfStudents based on isTechnologyBased
        // $numberOfStudents = $programmeData['approvedStream'] * ($programmeData['isTechnologyBased'] ? 40 : 60);
        $expirationDate = date('Y-m-d', strtotime($programmeData['expirationDate']));
            $yearExpiration = date('Y', strtotime($expirationDate)); // Extract the year part from expirationDate
            
            $yearGranted = $programmeData['yearGrantedInterimOrAccreditation'];

            // Calculate the gap in years
            $gap = $yearExpiration - $yearGranted; // Gap in years
            $currentDate = strtotime('now');
            $accreditationStatus="";
            // Determine accreditationStatus based on the gap
            if ($expirationDate < $currentDate) {
                $accreditationStatus = Status::EXPIRED;
            } elseif ($gap == 5) {
                $accreditationStatus = Status::ACCREDITED;
            } elseif ($gap == 2) {
                $accreditationStatus = Status::APPROVED;
            } elseif ($gap == 1) {
                $accreditationStatus = Status::INTERIM;
            } else {
                // Reject as invalid input
                return response()->json(['error' => 'Invalid gap between expirationDate and yearGrantedInterimOrAccreditation'], 400);
            }
        $programme = new Programme([
            'name' => $programmeData['name'],
            'yearGrantedInterimOrAccreditation' => $programmeData['yearGrantedInterimOrAccreditation'],
            'yearApproved' => $programmeData['yearApproved'],
            'accreditationStatus' => $accreditationStatus,
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

public function deleteIshiaguPolyInstitution(Request $request)
{
    // Validate the request if needed

    // Find the institution with the name "Afrihub Ict Institute, Abuja"
    $institution = Institution::where('name', 'Federal College Of Forest Resources Management, Ishiagu')->first();

    if (!$institution) {
        return response()->json(['error' => 'Institution not found'], 404);
    }

    // Delete the institution and cascade delete its associated programmes
    $institution->delete();

    return response()->json(['message' => 'Institution and associated programmes deleted successfully'], 200);
}
// Kano State Polytechnic, Kano State
}
