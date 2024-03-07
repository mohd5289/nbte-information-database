<?php

namespace App\Http\Controllers;

use App\Models\TechnicalCollegeInstitution;
use App\Models\TechnicalCollegeProgramme;
use Illuminate\Http\Request;

class TechnicalCollegesController extends Controller
{
    //

    public function getAllInstitutionsAndProgrammes(Request $request)
    {
        $institutionsWithPrograms = TechnicalCollegeInstitution::with('programmes')->get();
        $query = TechnicalCollegeInstitution::query();

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
    public function createInstitutionWithProgrammes(Request $request){
        $validatedData = $request->validate([
            'institution_name' => 'required|string',
            'programmes' => 'required|array',
            'programmes.*.name' => 'required|string',
            'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
            'programmes.*.yearApproved' => 'required|integer',
            'programmes.*.accreditationStatus' => 'required|string',
            'programmes.*.approvedStream' => 'required|integer|min:0',
            // Add this rule
            // Add other validation rules as needed
        ]);
    
        $existingInstitution = TechnicalCollegeInstitution::where('name', $validatedData['institution_name'])->first();
    
        if ($existingInstitution) {
            // If institution with the same name exists, associate programmes with the existing institution
            $institution = $existingInstitution;
        } else {
            // If institution with the same name doesn't exist, create a new one
            $institution = TechnicalCollegeInstitution::create([
                'name' => $validatedData['institution_name'],
                // Add other institution attributes here
            ]);
        }
        $uniqueProgramNames = [];
        foreach ($validatedData['programmes'] as $programmeData) {
            if (in_array($programmeData['name'], $uniqueProgramNames)) {
                return response()->json(['error' => 'Program with the same name already exists in this institution'], 400);
            }
            $uniqueProgramNames[] = $programmeData['name'];
            // Calculate numberOfStudents based on isTechnologyBased
            // $numberOfStudents = $programmeData['approvedStream'] * ($programmeData['isTechnologyBased'] ? 40 : 60);
            $expirationDate = null;
            switch ($programmeData['accreditationStatus']) {
                case 'Accredited':
                    $expirationDate = date('Y-m-d', strtotime('+5 years', strtotime($programmeData['yearGrantedInterimOrAccreditation'] . '-10-01')));
                    break;
                case 'Interim':
                    $expirationDate = date('Y-m-d', strtotime('+1 year', strtotime($programmeData['yearGrantedInterimOrAccreditation'] . '-10-01')));
                    break;
                case 'Approved':
                    $expirationDate = date('Y-m-d', strtotime('+2 years', strtotime($programmeData['yearGrantedInterimOrAccreditation'] . '-10-01')));
                    break;
                // Add more cases if needed for other accreditation statuses
            }
        
            $programme = new TechnicalCollegeProgramme([
                'name' => $programmeData['name'],
                'yearGrantedInterimOrAccreditation' => $programmeData['yearGrantedInterimOrAccreditation'],
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
}
