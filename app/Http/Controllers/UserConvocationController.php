<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserConvocationController extends Controller
{
    //
    public function createUserConvocations(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'convocation_id' => 'required',
                'user_id' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $user = User::findOrFail($validData['user_id']);
            $convocationId = $validData['convocation_id'];

            // Crear el registro en la tabla intermedia con 'status' establecido en 'false'
            $user->convocation()->attach($convocationId, ['status' => false]);

            return response()->json([
                'message' => 'Convocatoria creada'
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al crear la convocatoria ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al crear la convocatoria'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
