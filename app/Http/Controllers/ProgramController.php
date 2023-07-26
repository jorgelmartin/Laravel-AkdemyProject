<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{
    //
    public function getAllPrograms()
    {
        try {
            $services = Program::get();
            return response()->json([
                'message' => 'Programs retrieved',
                'data' => $services
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting programs ' .
                $th->getMessage());
            return response()->json([
                'message' => 'Error retrieving programs'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
