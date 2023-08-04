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
            // Verificar si el usuario tiene el rol de administrador (role: 1)
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

            // Convertir el campo "beginning" a formato de fecha válido
            $beginning = Carbon::parse($validData['beginning'])->toDateTimeString();

            $convocation = Convocation::create([
                'program_id' => $validData['program_id'],
                'beginning' => $beginning,
                'schedule' => $validData['schedule']
            ]);

            return response()->json([
                'message' => 'Convocatoria creada',
                'data' => $convocation
            ]);
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
            // Verificar role
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

            // Convertir la fecha utilizando Carbon
            $beginning = Carbon::parse($validData['beginning'])->toDateTimeString();

            // Buscar la convocatoria existente por su ID
            $convocation = Convocation::findOrFail($convocationId);

            // Actualizar los datos de la convocatoria
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

    //JOIN CONVOCATION
    public function joinConvocation(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();
            $userId = auth()->user()->id;
            $convocation = Convocation::find($validData['id']);
            if ($convocation === null) {
                return response()->json([
                    'message' => 'Convocation not found'
                ], Response::HTTP_NOT_FOUND);
            }
            // Verificar si el usuario ya está unido a la convocatoria.
            $existingUser = $convocation->user->find($userId);
            if ($existingUser) {
                if ($existingUser->pivot->pending) {
                    return response()->json([
                        'message' => 'User already has a pending join request for the convocation'
                    ], Response::HTTP_BAD_REQUEST);
                } else {
                    return response()->json([
                        'message' => 'User is already joined to the convocation'
                    ], Response::HTTP_BAD_REQUEST);
                }
            }

            // Agregar una solicitud pendiente para unir al usuario a la convocatoria.
            $convocation->user()->attach($userId, ['pending' => true]);

            return response()->json([
                'message' => 'Convocation join request sent',
                'data' => $convocation
            ]);
        } catch (\Throwable $th) {
            Log::error('Error joining convocation ' . $th->getMessage());
            return response()->json([
                'message' => 'Error joining convocation'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
};