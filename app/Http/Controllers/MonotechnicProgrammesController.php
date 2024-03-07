<?php

namespace App\Http\Controllers;

use App\Models\MonotechnicInstitution;
use App\Models\MonotechnicProgramme;

use Illuminate\Http\Request;

class MonotechnicProgrammesController extends Controller
{
    //
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
    
        $existingInstitution = MonotechnicInstitution::where('name', $validatedData['institution_name'])->first();
    
        if ($existingInstitution) {
            // If institution with the same name exists, associate programmes with the existing institution
            $institution = $existingInstitution;
        } else {
            // If institution with the same name doesn't exist, create a new one
            $institution = MonotechnicInstitution::create([
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
        
            $programme = new MonotechnicProgramme([
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
