<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;

class InstitutionController extends Controller
{
    //

    public function index()
    {
        $institutions = Institution::all();

        return response()->json(['institutions' => $institutions]);
    }
}
