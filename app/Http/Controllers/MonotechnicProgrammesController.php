<?php

namespace App\Http\Controllers;

use App\Models\MonotechnicInstitution;
use App\Models\MonotechnicProgramme;
use App\Models\Status;
use App\Models\SubDepartment;
use Illuminate\Http\Request;

class MonotechnicProgrammesController extends Controller
{
    //
    public function getAllInstitutionsAndCollegeOfAgricultureProgrammes(Request $request)
    {
        $subDepartmentName = 'College of Agriculture';
    
        // Get the sub-department ID for College of Agriculture
        $subDepartmentId = SubDepartment::where('name', $subDepartmentName)->value('id');
    
        $query = MonotechnicInstitution::query()->with(['programmes' => function ($query) use ($subDepartmentId) {
            // Filter programmes by sub-department ID
            $query->where('sub_department_id', $subDepartmentId);
        }]);
    
        // Your existing filtering logic can remain unchanged
    
        // Execute the query
        $institutionsWithPrograms = $query->get();
    
        $data = [];
    
        // Format the response data
        foreach ($institutionsWithPrograms as $institution) {
            $data[] = [
                'institution_name' => $institution->name,
                'programmes' => $institution->programmes->toArray(),
            ];
        }
    
        // Return the response
        return response()->json(['data' => $data]);
    }


    public function getAllInstitutionsAndCollegeOfHealthSciencesProgrammes(Request $request)
{
    $subDepartmentName = 'College of Health Sciences';

    // Get the sub-department ID for College of Agriculture
    $subDepartmentId = SubDepartment::where('name', $subDepartmentName)->value('id');

    $query = MonotechnicInstitution::query()->with(['programmes' => function ($query) use ($subDepartmentId) {
        // Filter programmes by sub-department ID
        $query->where('sub_department_id', $subDepartmentId);
    }]);

    // Your existing filtering logic can remain unchanged

    // Execute the query
    $institutionsWithPrograms = $query->get();

    $data = [];

    // Format the response data
    foreach ($institutionsWithPrograms as $institution) {
        $data[] = [
            'institution_name' => $institution->name,
            'programmes' => $institution->programmes->toArray(),
        ];
    }

    // Return the response
    return response()->json(['data' => $data]);
}



public function getAllInstitutionsAndSpecialisedInstitutionProgrammes(Request $request)
{
    $subDepartmentName = 'Specialised Institution';

    // Get the sub-department ID for College of Agriculture
    $subDepartmentId = SubDepartment::where('name', $subDepartmentName)->value('id');

    $query = MonotechnicInstitution::query()->with(['programmes' => function ($query) use ($subDepartmentId) {
        // Filter programmes by sub-department ID
        $query->where('sub_department_id', $subDepartmentId);
    }]);

    // Your existing filtering logic can remain unchanged

    // Execute the query
    $institutionsWithPrograms = $query->get();

    $data = [];

    // Format the response data
    foreach ($institutionsWithPrograms as $institution) {
        $data[] = [
            'institution_name' => $institution->name,
            'programmes' => $institution->programmes->toArray(),
        ];
    }

    // Return the response
    return response()->json(['data' => $data]);
}

    public function getAllInstitutionsAndProgrammes(Request $request)
    {
        $institutionsWithPrograms = MonotechnicInstitution::with('programmes')->get();
        $query = MonotechnicInstitution::query();

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

    public function createInstitutionWithCollegeOfAgricultureProgrammes(Request $request){
        $validatedData = $request->validate([
            'institution_name' => 'required|string',
            'programmes' => 'required|array',
            'programmes.*.name' => 'required|string',
            'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
            'programmes.*.yearApproved' => 'required|integer',
            'programmes.*.approvedStream' => 'required|integer|min:0',
            'programmes.*.expirationDate' => 'required|date',
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
        $subDepartmentName = 'College of Agriculture'; // Assuming this is the sub-department name

        $subDepartment = SubDepartment::where('name', $subDepartmentName)->first();
    
        if (!$subDepartment) {
            // Create the sub-department if it doesn't exist
            $subDepartment = SubDepartment::create(['name' => $subDepartmentName]);
        }
        $uniqueProgramNames = [];
        foreach ($validatedData['programmes'] as $programmeData) {
            if (in_array($programmeData['name'], $uniqueProgramNames)) {
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
            $programme = new MonotechnicProgramme([
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
            $programme->subDepartment()->associate($subDepartment);

            $programme->institution()->associate($institution);
            // Save the programme
            $programme->save();
            // Associate the programme with the institution
            // $institution->programmes()->save($programme);
        }
        return response()->json(['message' => 'Institution and programmes created successfully'], 201);
    }







    public function createInstitutionWithCollegeOfHealthSciencesProgrammes(Request $request){
        $validatedData = $request->validate([
            'institution_name' => 'required|string',
            'programmes' => 'required|array',
            'programmes.*.name' => 'required|string',
            'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
            'programmes.*.yearApproved' => 'required|integer',
           
            'programmes.*.approvedStream' => 'required|integer|min:0',
            'programmes.*.expirationDate' => 'required|date',
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
        $subDepartmentName = 'College of Health Sciences'; // Assuming this is the sub-department name

        $subDepartment = SubDepartment::where('name', $subDepartmentName)->first();
    
        if (!$subDepartment) {
            // Create the sub-department if it doesn't exist
            $subDepartment = SubDepartment::create(['name' => $subDepartmentName]);
        }
        $uniqueProgramNames = [];
        foreach ($validatedData['programmes'] as $programmeData) {
            if (in_array($programmeData['name'], $uniqueProgramNames)) {
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
            $programme = new MonotechnicProgramme([
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
            $programme->subDepartment()->associate($subDepartment);
            $programme->institution()->associate($institution);
            // Save the programme
            $programme->save();
            // Associate the programme with the institution
            // $institution->programmes()->save($programme);
        }
        return response()->json(['message' => 'Institution and programmes created successfully'], 201);
    }





    public function createInstitutionWithSpecialisedInstitutionProgrammes(Request $request){
        $validatedData = $request->validate([
            'institution_name' => 'required|string',
            'programmes' => 'required|array',
            'programmes.*.name' => 'required|string',
            'programmes.*.yearGrantedInterimOrAccreditation' => 'required|integer',
            'programmes.*.yearApproved' => 'required|integer',
            'programmes.*.approvedStream' => 'required|integer|min:0',
            'programmes.*.expirationDate' => 'required|date',
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
        $subDepartmentName = 'Specialised Institution'; // Assuming this is the sub-department name

        $subDepartment = SubDepartment::where('name', $subDepartmentName)->first();
    
        if (!$subDepartment) {
            // Create the sub-department if it doesn't exist
            $subDepartment = SubDepartment::create(['name' => $subDepartmentName]);
        }
        $uniqueProgramNames = [];
        foreach ($validatedData['programmes'] as $programmeData) {
            if (in_array($programmeData['name'], $uniqueProgramNames)) {
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
            $programme = new MonotechnicProgramme([
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
            $programme->subDepartment()->associate($subDepartment);
            
            $programme->institution()->associate($institution);
            // Save the programme
            $programme->save();
            // Associate the programme with the institution
            // $institution->programmes()->save($programme);
        }
        return response()->json(['message' => 'Institution and programmes created successfully'], 201);
    }

}
