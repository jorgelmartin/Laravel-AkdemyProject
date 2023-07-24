<?php

namespace App\Http\Controllers;

use App\Models\Convocation;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ConvocationController extends Controller
{
    //
    public function getAllConvocations()
    {
        try {
            // Paso 1: Obtener el user_id de la solicitud
            $userId = auth()->user()->id;

            // Paso 2: Encontrar el usuario por su clave primaria (asumiendo que se usa el modelo User)
            $user = User::find($userId);

            // Paso 3: Verificar si el usuario tiene role_id igual a 2 (dentista)
            if ($user->role_id == 1) {
                // Paso 4: Obtener todas las convocatorias cuando el usuario es un dentista
                $convocations = Convocation::with(['user', 'program'])
                    ->get([
                        'id',
                        'user_id',
                        'program_id',
                        'beginning',
                        'end'
                    ]);
            } else {
                // Paso 5: Obtener las convocatorias cuando el usuario es un paciente
                $convocations = Convocation::with(['user', 'program'])
                    ->where('user_id', $userId)
                    ->get([
                        'id',
                        'user_id',
                        'program_id',
                        'beginning',
                        'end'
                    ]);
            }

            // Paso 6: Devolver la respuesta JSON con los datos
            return response()->json([
                'success' => true,
                'message' => 'Todas las convocatorias recuperadas',
                'data' => $convocations,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error al obtener las convocatorias: ' . $th->getMessage());
            return response()->json([
                'message' => 'Error al recuperar las convocatorias'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
};
