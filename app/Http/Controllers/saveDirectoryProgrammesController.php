<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Institution;
use App\Models\Programme;
use App\Models\Status;
use DateTime;
use Illuminate\Http\Request;

class saveDirectoryProgrammesController extends Controller
{
    //
    public function storeFirstProgramme()
    {
      $institution = new Institution(['name'=>'Auchi Polytechnic, Auchi']);
      $institution->save();
        // ... [rest of the code]
        $auchiProgramme = new Programme(['name'=>'ND Agricultural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2025-10-01')]);
        $institution->programmes()->save($auchiProgramme);
        return response()->json([
            'message' => 'Institution and programmes saved successfully!',
            'institution' => $institution,
            'programmes' => $institution->programmes,
        ], 201);
    }
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

      public function storeRemainingProgrammesInAuchi(){
        $auchi= Institution::where('name', 'Auchi Polytechnic, Auchi')->first();
        $remainingProgrammes = [new Programme(['name'=>'HND Art & Industrial Design (Ceramics)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Art & Industrial Design (Graphics)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Art & Industrial Design (Painting)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Art & Industrial Design (Sculpture)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Art & Industrial Design (Textiles)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'ND Art & Industrial Design ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'ND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Library & Information Science', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-01-21')]),
       
        new Programme(['name'=>'HND Business Administration & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Office Technology and Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Public Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Business Administration & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'ND Office Technology and Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Public Administration ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'ND Microfinace and Enterprise Development', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2025-01-21')]),   
        new Programme(['name'=>'ND Procurement & Supply Chain Management', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2025-01-21')]),    
        
        new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Farm Power Machinery)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Agric & Bio-Environ. Engr.g (Soil & Water)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'HND Agricultural and Bio-Environmental Engineering Technology(Farm Power option)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Chemical Engineering Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]), 
        new Programme(['name'=>'HND Civil Engineering Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]), 
        new Programme(['name'=>'HND Electrical Engineering (Electronics & Telecom)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Electrical Engineering (Power & Machine)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'HND Geological Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Mechanical Engineering (Manufacturing)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 

        new Programme(['name'=>'HND Mechanical Engineering (Plant Engineering)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Mining Engineering Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Minerals and Petroleum Engineering Technology (Geology option)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'HND Minerals and Petroleum Engineering Technology (Mining option)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'ND Agricultural & Bio-Environmental Engineering ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'ND Chemical Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'ND Civil Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]), 
        new Programme(['name'=>'ND Electrical/Electronic Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'ND Mechanical Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'ND Mineral Resources Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2028-01-21')]), 
        new Programme(['name'=>'ND Welding & Fabrication Engineering', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2025-01-21')]), 
   
        new Programme(['name'=>'HND Architectural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Building Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Estate Management & Valuation', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Quantity Surveying ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Surveying  & Geoinformatics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'HND Urban and Regional Planning ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Architectural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
        new Programme(['name'=>'ND Building Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Estate Management & Valuation', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Quantity Surveying', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Surveying & Geoinformatics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Urban and Regional Planning', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'P-HND Building Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2025-01-07')]),
        new Programme(['name'=>'P-HND Urban and Regional Planning', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2028-01-21')]),
    
        new Programme(['name'=>'HND Accountancy', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Banking and Finance', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Accountancy', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Banking and Finance', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Taxation', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2025-01-21')]),
    
        new Programme(['name'=>'HND Mass Communication', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Mass Communication ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Hospitality Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
        new Programme(['name'=>'ND Hospitality Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2028-01-21')]),
    
    
        new Programme(['name'=>'HND Computer Science', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'HND Food Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Geological Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>0,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2019-10-01')]),
        new Programme(['name'=>'HND Polymer Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'HND S.L.T. (Biology/Microbiology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND S.L.T. (Chemistry)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND S.L.T. (Chemistry/Biochemistry)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        
        
        new Programme(['name'=>'HND S.L.T. (Microbiology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND S.L.T. (Physics with Electronics)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'HND Statistics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-21')]),
        new Programme(['name'=>'ND Ceramic Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Computer Science', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
        new Programme(['name'=>'ND Food Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Polymer Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Science Laboratory Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Statistics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2028-01-21')]),
        new Programme(['name'=>'ND Geological Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2025-01-21')]),
        new Programme(['name'=>'ND Tourism Management Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2025-01-21')]),
        new Programme(['name'=>'Institutional Administration', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>0,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::INSTITUTIONAL, 'expirationDate'=>new DateTime('2026-10-01')]),
    ];

    $auchi->programmes()->saveMany($remainingProgrammes);
    return response()->json([
        'message' => 'Institution and programmes saved successfully!',
        'institution' => $auchi,
        'programmes' => $auchi->programmes,
    ], 201);
      }


      public function saveKadunaPolytechnicProgrammes(){
        $kadunaPolytechnic = new Institution(["name"=>"Kaduna Polytechnic, Kaduna"]);
        $kadunaPolytechnic->save();

    $kadunaPolyProgrammes = [new Programme(['name'=>'ND Agricultural Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2019-10-01')]),
    
    new Programme(['name'=>'HND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Printing Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Printing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>7,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'P-HND Printing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2009, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2014-10-01')]),
    
    new Programme(['name'=>'HND Business Administration & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Co-operative Economics & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Human Resources Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Local Government studies', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),

    new Programme(['name'=>'HND Production & Operation Management ', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Public Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Purchasing & Supply', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Office Technology & Management ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Social Development (Community Dev. & Adult Education)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),

    new Programme(['name'=>'HND Social Development (Social Welfare)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Social Development (Youth & Sport)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Psycho-Social Rehabilitation', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Business Administration & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'ND Co-operative Economics & Management ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Local Government Studies', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Psycho-Social Rehabilitation', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Public Administration ', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'ND Office Technology & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Social Development ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Industrial and Labour Relations', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Marketing', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Procurement and Supply Chain Management', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),

    new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Farm Power Machinery)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Soil & Water)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2019-10-01')]),
    new Programme(['name'=>'HND Chemical Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Civil Engineering Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2016-10-01')]),
    new Programme(['name'=>'HND Computer Engineering ', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Electrical Engineering (Electronics & Telecom)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Electrical Engineering (Power & Machine)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2011, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND I.S.E.E.T (Environment Engineering)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2016-10-01')]),
    new Programme(['name'=>'HND Mechanical Engineering (Manufacturing)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Mechanical Engineering (Power & Plant)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2019-10-01')]),   

    new Programme(['name'=>'HND M.P.R.E. (Geological Engineering Technology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND M.P.R.E. (Minerals Engineering)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2019-10-01')]),  
    new Programme(['name'=>'HND M.P.R.E. (Mining Engineering Technology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),   
    new Programme(['name'=>'HND Agricultural and Bio-Environmental Engineering Technology (Post-Harvest Option)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  
    new Programme(['name'=>'HND Petroleum Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),   

    new Programme(['name'=>'HND Electrical/Electronic Engineering Technology (Control and Instrumentation Option)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),   
    new Programme(['name'=>'ND Agricultural & Bio-Environmental Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>8,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2019-10-01')]),   
    new Programme(['name'=>'ND Chemical Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>9,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),   
    new Programme(['name'=>'ND Civil Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>7,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),   
    new Programme(['name'=>'ND Renewable Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  

    new Programme(['name'=>'ND Foundry Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  
    new Programme(['name'=>'ND Water Resources Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  
    new Programme(['name'=>'ND Mechatronics Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  
    new Programme(['name'=>'ND Petroleum and Gas Processing Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  
    new Programme(['name'=>'ND Metallurgical Engineering Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-03-01')]),  

    new Programme(['name'=>'ND Computer Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>8,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2020-10-01')]), 
    new Programme(['name'=>'ND Electrical/Electronic Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>8,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),  
    new Programme(['name'=>'ND Industrial Safety & Environmental Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),  
    new Programme(['name'=>'ND Mechanical Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2019-10-01')]),  
    new Programme(['name'=>'ND Mineral & Petroleum Resources Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),  
    new Programme(['name'=>'ND Railway Engineering Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2021-10-01')]), 

    new Programme(['name'=>'HND Architectural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'HND Building Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'HND Cartography & Geographic Information Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2020-10-01')]), 
    new Programme(['name'=>'HND Estate Management & Valuation', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'HND Photogrammetry & Remote Sensing', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2020-10-01')]), 

    new Programme(['name'=>'HND Quantity Surveying', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'HND Surveying & Geoinformatics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'HND Urban & Regional Planning', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'ND Architectural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'ND Building Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 

    new Programme(['name'=>'ND Cartography & Geographic Information Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2020-10-01')]), 
    new Programme(['name'=>'ND Estate Management & Valuation', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'ND Photogrammetry & Remote Sensing', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2020-10-01')]), 
    new Programme(['name'=>'ND Quantity Surveying', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 
    new Programme(['name'=>'ND Surveying & Geoinformatics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]), 

    new Programme(['name'=>'ND Urban & Regional Planning', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'P-HND Urban & Regional Planning', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Banking & Finance', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Accountancy', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Accountancy', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),

    new Programme(['name'=>'ND Banking & Finance', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Hospitality Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Leisure & Tourism', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Nutrition & Dietetics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Hospitality Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),

    new Programme(['name'=>'ND Nutrition & Dietetics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Leisure & Tourism Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Library & Information Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Mass Communication', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Library & Information Science', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),

    new Programme(['name'=>'ND Mass Communication', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Computer Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2015, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2020-10-01')]),
    new Programme(['name'=>'HND Food Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND S.L.T. (Biology /Microbiology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND S.L.T. (Chemistry/Biochemistry)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
   
    new Programme(['name'=>'HND S.L.T. (Microbiology/Biochemistry)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2024-03-01')]),
    new Programme(['name'=>'HND S.L.T. (Enviromental Biology)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2024-03-01')]),
    new Programme(['name'=>'HND S.L.T. (Biochemistry)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2024-03-01')]),
    new Programme(['name'=>'HND S.L.T. (Chemistry)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2024-03-01')]),
    new Programme(['name'=>'HND S.L.T. (Microbiology)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),

    new Programme(['name'=>'HND S.L.T (Physics with Electronics)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Statistics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'HND Textile Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Computer Science', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>7,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Food Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2011, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2016-10-01')]),

    new Programme(['name'=>'ND Science Laboratory Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Statistics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>8,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Textile Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
    new Programme(['name'=>'ND Polymer Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2024-11-18')]),
    new Programme(['name'=>'Institutional Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>0,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::INSTITUTIONAL, 'expirationDate'=>new DateTime('2024-10-01')]),
    
    

];
$kadunaPolytechnic->programmes()->saveMany($kadunaPolyProgrammes);
return response()->json([
    'message' => 'Institution and programmes saved successfully!',
    'institution' => $kadunaPolytechnic,
    'programmes' => $kadunaPolytechnic->programmes,
], 201);
    
    }






public function saveNekedePolyProgrammes(){
$federalPolytechnicNekede = new Institution(['name'=>'Federal Polytechnic, Nekede, Owerri']);

$federalPolytechnicNekede->save();

$fedPolyNekedeProgrammes= [new Programme(['name'=>'HND Agricultural Extension & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'HND Crop Production Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'HND Fisheries Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2018-10-01')]),
new Programme(['name'=>'ND Agricultural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Fisheries Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Animal Health & Production Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Soil Science & Land Resources Management', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2024-02-01')]),
new Programme(['name'=>'ND Hydrology & Water Resources Management', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2024-02-01')]),


new Programme(['name'=>'HND Art & Industrial Design (Graphics)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'HND Art & Industrial Design (Painting)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'HND Art & Industrial Design (PCeramics)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Art & Industrial Design (Sculpture)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Art & Industrial Design (Textile)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Art & Industrial Design', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Printing Technology', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND Business Administration & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Co-operative Economics and Management ', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Public Administration', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Procurement and  Supply Chain Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Office Technology & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'ND Business Administration & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Co-operative Economics and Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Office Technology & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Public Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Purchasing Supply', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Farm Power Machinery)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Post Harvest Option)', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-02-01')]),
new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Soil & Water)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2016, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Chemical Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2018-10-01')]),
new Programme(['name'=>'HND Civil Engineering Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND Computer Engineering', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Electrical Engineering (Electronics & Telecom)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Electrical Engineering (Instrumentation & Control)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Electrical Engineering (Power & Machine)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Mechanical Engineering (Manufacturing)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Mechanical Engineering (Power & Plant)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Mechatronics Engineering Tech (Automotive )', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2021-10-01')]),
new Programme(['name'=>'HND Mining Engineering Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2022, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2024-02-01')]),
new Programme(['name'=>'ND Agricultural & Bio-Environmental Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Chemical Engineering', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),

new Programme(['name'=>'ND Civil Engineering', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Computer Engineering', 'accreditationStatus'=> Status::INTERIM ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Electrical/Electronic Engineering', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Mechanical Engineering', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Mechatronics Engineering', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),

new Programme(['name'=>'ND Metallurgical Engineering Technology', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2020-10-01')]),
new Programme(['name'=>'ND Mineral & Petroleum Resources Engineering', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Welding and Fabrication Engineering Technology', 'accreditationStatus'=> Status::INTERIM ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND Architectural Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Building Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Estate Management & Valuation', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Quantity Surveying', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Surveying & Geoinformatics', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Urban & Regional Planning', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Architectural Technology', 'accreditationStatus'=> Status::INTERIM ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Building Technology', 'accreditationStatus'=> Status::INTERIM ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Estate Management & Valuation', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND  Quantity Surveying', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),


new Programme(['name'=>'ND Surveying & Geoinformatics', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Urban & Regional Planning', 'accreditationStatus'=> Status::INTERIM ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Accountancy', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Banking & Finance', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Taxation', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2019-10-01')]),

new Programme(['name'=>'ND Accountancy', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Banking & Finance', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Taxation', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Library and Information Science', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Mass Communication', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2022-10-01')]),


new Programme(['name'=>'ND Library and Information Science', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Mass Communication', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Hospitality Management', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'ND Hospitality Management', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Computer Science', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),

new Programme(['name'=>'HND Food Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Pharmaceutical Technology', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2019-10-01')]),
new Programme(['name'=>'HND S.L.T. ( Biochemistry/Microbiology)', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND S.L.T. ( Biology/Microbiology)', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND S.L.T. (Chemistry)', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),


new Programme(['name'=>'HND S.L.T. (Environmental Biology)', 'accreditationStatus'=> Status::EXPIRED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND S.L.T. (Physics with Electronics )', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Statistics', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Computer Science', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>7,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Dispensing Opticianry', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Food Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'ND Pharmaceutical Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Science Laboratory Technology', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>5,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Statistics', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'Institutional Administration', 'accreditationStatus'=> Status::ACCREDITED ,'approvedStream'=>0,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INSTITUTIONAL, 'expirationDate'=>new DateTime('2023-10-01')]),


];
$federalPolytechnicNekede->programmes()->saveMany($fedPolyNekedeProgrammes);
return response()->json([
    'message' => 'Institution and programmes saved successfully!',
    'institution' => $federalPolytechnicNekede,
    'programmes' => $federalPolytechnicNekede->programmes,
], 201);
}




public function saveFedPolyBauchiProgrammes(){
$federalPolytechnicBauchi= new Institution(['name'=>'FEDERAL POLYTECHNIC, BAUCHI']);

$federalPolytechnicBauchi->save();

$fedPolyBauchiProgrammes= [
new Programme(['name'=>'HND Forestry Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Crop Production Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Agricultural Extension and Management Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Agricultural Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Animal Health and Production Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'ND Forestry Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Fisheries Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Business Administration & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Office Technology & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Public Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Business Administration & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Marketing', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Office Technology & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Public Administration', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'ND Crime Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Co-operative Economics & Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Taxation', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::BUSINESS, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Farm Power Machinery)', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Post Harvest)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Agric & Bio-Environ. Eng’g (Soil & Water Engineering)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Civil Engineering Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Electrical Engineering (Electronics & Telecom)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Electrical Engineering (Power & Machines)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Mechanical Engineering (Manufacturing)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Mechanical Engineering (Power & Plant)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Agricultural & Bio-Environmental Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Civil Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Electrical/ Electronic Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Mechanical Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'ND Mechatronics Engineering', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2025-10-01')]),
new Programme(['name'=>'ND Computer Engineering', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENGINEERING, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Architectural Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Building Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Estate Management & Valuation', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Quantity Surveying', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Surveying & Geo informatics', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Architectural Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Building Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Estate Management & Valuation', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>3,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),

new Programme(['name'=>'ND Quantity Surveying', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Surveying & Geo informatics', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ENVIRONMENTAL, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Accountancy', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Banking and Finance', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Accountancy', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Banking and Finance', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::FINANCE, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Hospitality Management Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Hospitality Management Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'HND Nutrition & Dietetics', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Hospitality Management Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Tourism Management Technology Management', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Nutrition & Dietetics', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'HND Mass Communication', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2025-10-01')]),
new Programme(['name'=>'HND Library & Information Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'ND Library & Information Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2020-10-01')]),
new Programme(['name'=>'ND Mass Communication', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::INFORMATION, 'expirationDate'=>new DateTime('2022-10-01')]),


new Programme(['name'=>'HND Computer Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND Food Science & Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'HND S.L.T. (Biology/Microbiology)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND S.L.T. (Chemistry/Biochemistry)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND S.L.T. (Physics with Electronics)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),

new Programme(['name'=>'HND SLT Microbiology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2023-10-01')]),
new Programme(['name'=>'HND Statistics', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Computer Science', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>6,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Food Science & Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2026-10-01')]),
new Programme(['name'=>'ND Science Laboratory Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>4,  'yearGrantedInterimOrAccredition'=> 2018, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),
new Programme(['name'=>'ND Statistics', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::SCIENCE, 'expirationDate'=>new DateTime('2022-10-01')]),

new Programme(['name'=>'Institutional Administration', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>0,  'yearGrantedInterimOrAccredition'=> 2021, 'faculty'=> Faculty::INSTITUTIONAL, 'expirationDate'=>new DateTime('2026-10-01')]),
];

$federalPolytechnicBauchi->programmes()->saveMany($fedPolyBauchiProgrammes);
return response()->json([
    'message' => 'Institution and programmes saved successfully!',
    'institution' => $federalPolytechnicBauchi,
    'programmes' => $federalPolytechnicBauchi->programmes,
], 201);
}





public function saveYabaTechProgrammes(){


    $yabaCollegeOfTechnology= new Institution(['name'=>'YABA COLLEGE OF TECHNOLOGY, LAGOS (EPE CAMPUS)']);

$yabaCollegeOfTechnology->save();


$yabaCollegeProgrammes=[
    new Programme(['name'=>'HND Agricultural Extension & Management', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2024-10-01')]),
    new Programme(['name'=>'HND Animal Production Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2021-10-01')]),
    new Programme(['name'=>'HND Crop Production Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2021-10-01')]),
    new Programme(['name'=>'ND Agricultural Technology', 'accreditationStatus'=> Status::ACCREDITED,'approvedStream'=>2,  'yearGrantedInterimOrAccredition'=> 2019, 'faculty'=> Faculty::AGRIC, 'expirationDate'=>new DateTime('2024-10-01')]),

    new Programme(['name'=>'ND Tourism Management Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2009, 'faculty'=> Faculty::HOSPITALITY, 'expirationDate'=>new DateTime('2014-10-01')]),
    new Programme(['name'=>'HND Art & Industrial Design (Ceramics)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Art & Industrial Design (Graphics)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Art & Industrial Design (Painting )', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Art & Industrial Design (Sculpture)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),

    new Programme(['name'=>'HND Art & Industrial Design (Textiles)', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'HND Printing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Art & Industrial Design', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Fashion Design & Clothing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Printing Technology', 'accreditationStatus'=> Status::EXPIRED,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2017, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2022-10-01')]),
    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),

    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),
    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),
    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),
    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),
    new Programme(['name'=>'ND Photography', 'accreditationStatus'=> Status::INTERIM,'approvedStream'=>1,  'yearGrantedInterimOrAccredition'=> 2023, 'faculty'=> Faculty::ART, 'expirationDate'=>new DateTime('2025-10-01')]),


];

}

    }

