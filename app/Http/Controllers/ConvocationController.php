<?php

namespace App\Http\Controllers;

use App\Models\Convocation;
use App\Models\User;
use App\Models\Program;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ConvocationController extends Controller
{
    //GET ALL CONVOCATIONS
    public function getAllConvocations()
    {
        try {
            $convocation = Convocation::with(['user', 'program'])->get();
            return response()->json([
                'message' => 'Convocations retrieved',
                'data' => $convocation
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting convocations: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error retrieving convocations'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // CREATE CONVOCATION (ADMIN)
    public function createConvocations(Request $request)
    {
        try {
            if (auth()->user()->role_id !== 1) {
                return response()->json(['message' => 'No tienes permiso para crear convocatorias'], 403);
            }

            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'beginning' => 'required',
                'schedule' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            // CONVERT 'beginning' IN VALID DATA
            $beginning = Carbon::parse($validData['beginning'])->toDateTimeString();

            $convocation = Convocation::create([
                'program_id' => $validData['program_id'],
                'beginning' => $beginning,
                'schedule' => $validData['schedule']
            ]);

            return response()->json([
                'message' => 'Convocatoria creada',
                'data' => $convocation
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error al crear la convocatoria ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al crear la convocatoria'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //UPDATE CONVOCATIONS (ADMIN)
    public function updateConvocations(Request $request, $convocationId)
    {
        try {
            if (auth()->user()->role_id !== 1) {
                return response()->json(['message' => 'No tienes permiso para actualizar convocatorias'], 403);
            }

            $validator = Validator::make($request->all(), [
                'program_id' => 'required',
                'beginning' => 'required',
                'schedule' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            // CONVERT 'beginning' IN VALID DATA
            $beginning = Carbon::parse($validData['beginning'])->toDateTimeString();

            $convocation = Convocation::findOrFail($convocationId);

            $convocation->update([
                'program_id' => $validData['program_id'],
                'beginning' => $beginning,
                'schedule' => $validData['schedule']
            ]);

            return response()->json([
                'message' => 'Convocation updated',
                'data' => $convocation
            ]);
        } catch (\Throwable $th) {
            Log::error('Error updating convocation ' . $th->getMessage());

            return response()->json([
                'message' => 'Error updating convocation'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
};
