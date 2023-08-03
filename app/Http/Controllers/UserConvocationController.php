<?php

namespace App\Http\Controllers;

// use App\Mail\RequestAccepted;
// use App\Mail\RequestCreated;
use App\Models\Convocation;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserConvocationController extends Controller
{
    //
    // public function createUserConvocations(Request $request)
    // {
    //     try {

            
    //         $validator = Validator::make($request->all(), [
    //             'convocation_id' => 'required',
    //             'user_id' => 'required'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json($validator->errors(), 400);
    //         }

    //         $validData = $validator->validated();

    //         $user = User::findOrFail($validData['user_id']);
    //         $convocationId = $validData['convocation_id'];

    //         // Verificar si ya existe una relación entre el usuario y la convocatoria
    //     $existingRelation = $user->convocation()->where('convocation_id', $convocationId)->exists();

    //     if ($existingRelation) {
    //         return response()->json([
    //             'message' => 'El usuario ya tiene una solicitud creada para esta convocatoria'
    //         ], Response::HTTP_BAD_REQUEST);
    //     }

    //         // Crear el registro en la tabla intermedia con 'status' establecido en 'false'
    //         $user->convocation()->attach($convocationId, ['status' => false]);

    //         // Enviar el correo electrónico
    //         // Mail::to($user->email)->send(new RequestCreated($user->name, $convocationId));

    //         return response()->json([
    //             'message' => 'Solicitud creada'
    //         ]);
    //     } catch (\Throwable $th) {
    //         Log::error('Error al crear la solicitud ' . $th->getMessage());

    //         return response()->json([
    //             'message' => 'Error al crear la solicitud'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

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

        // Verificar si ya existe una relación entre el usuario y la convocatoria
        $existingRelation = $user->convocation()->where('convocation_id', $convocationId)->exists();

        if ($existingRelation) {
            return response()->json([
                'message' => 'El usuario ya tiene una solicitud creada para esta convocatoria'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Crear el registro en la tabla intermedia con 'status' establecido en 'false'
        $user->convocation()->attach($convocationId, ['status' => false]);

        return response()->json([
            'message' => 'Solicitud creada'
        ]);
    } catch (\Throwable $th) {
        Log::error('Error al crear la solicitud ' . $th->getMessage());

        return response()->json([
            'message' => 'Error al crear la solicitud'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
public function acceptUserRequest(Request $request, $requestId)
{
    try {
        // Buscar la solicitud en la tabla intermedia por su ID
        $convocationRequest = DB::table('user_convocation')
            ->where('id', $requestId)
            ->first();

        if (!$convocationRequest) {
            return response()->json([
                'message' => 'La solicitud de convocatoria no existe'
            ], Response::HTTP_NOT_FOUND);
        }

        // Actualizar el estado de la solicitud a 'true' (aceptada)
        DB::table('user_convocation')
            ->where('id', $requestId)
            ->update(['status' => true]);

        // Aquí podrías realizar otras acciones adicionales relacionadas con la aceptación de la solicitud,
        // como notificar al usuario, enviar correos, etc.

        return response()->json([
            'message' => 'Solicitud de convocatoria aceptada exitosamente'
        ], Response::HTTP_OK);
    } catch (\Throwable $th) {
        Log::error('Error al aceptar la solicitud de convocatoria: ' . $th->getMessage());

        return response()->json([
            'message' => 'Error al aceptar la solicitud de convocatoria'
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
    // public function acceptUserRequest(Request $request, $requestId)
    // {
    //     try {
    //         // Buscar la solicitud en la tabla intermedia por su ID
    //         $convocationRequest = DB::table('user_convocation')
    //             ->where('id', $requestId)
    //             ->first();

    //         if (!$convocationRequest) {
    //             return response()->json([
    //                 'message' => 'La solicitud de convocatoria no existe'
    //             ], Response::HTTP_NOT_FOUND);
    //         }

    //         // Actualizar el estado de la solicitud a 'true' (aceptada)
    //         DB::table('user_convocation')
    //             ->where('id', $requestId)
    //             ->update(['status' => true]);


    //             // $user = User::find($convocationRequest->user_id);
    //     // Mail::to($user->email)->send(new RequestAccepted());
    //         // Aquí podrías realizar otras acciones adicionales relacionadas con la aceptación de la solicitud,
    //         // como notificar al usuario, enviar correos, etc.

    //         return response()->json([
    //             'message' => 'Solicitud de convocatoria aceptada exitosamente'
    //         ], Response::HTTP_OK);
    //     } catch (\Throwable $th) {
    //         Log::error('Error al aceptar la solicitud de convocatoria: ' . $th->getMessage());

    //         return response()->json([
    //             'message' => 'Error al aceptar la solicitud de convocatoria'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    //GET PENDING CONVOCATION REQUEST
    public function getPendingUserRequests(Request $request)
    {
        try {
            // Obtener todas las solicitudes pendientes (status = false) de la tabla intermedia
            $pendingRequests = DB::table('user_convocation')
                ->where('status', false)
                ->get();

            // Obtener la información de los usuarios y convocatorias relacionadas
            $requestsData = [];
            foreach ($pendingRequests as $request) {
                $user = User::find($request->user_id);
                $convocation = Convocation::find($request->convocation_id);

                if ($user && $convocation) {
                    // Obtener el programa relacionado con la convocatoria
                    $program = Program::find($convocation->program_id);

                    $requestsData[] = [
                        'id' => $request->id,
                        'status' => $request->status,
                        'user' => $user,
                        'convocation' => $convocation,
                        'program' => $program,
                    ];
                }
            }
            return response()->json($requestsData);
        } catch (\Throwable $th) {
            Log::error('Error al obtener las solicitudes de convocatoria pendientes: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al obtener las solicitudes de convocatoria pendientes'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMyAcceptedUserRequests(Request $request, $userId)
    {
        try {
            // Obtener todas las solicitudes aceptadas (status = true) de la tabla intermedia para el ID de usuario dado
            $acceptedRequests = DB::table('user_convocation')
                ->where('status', true)
                ->where('user_id', $userId)
                ->get();

            // Obtener la información de los usuarios, convocatorias y programas relacionados
            $requestsData = [];
            foreach ($acceptedRequests as $request) {
                $user = User::find($request->user_id);
                $convocation = Convocation::find($request->convocation_id);

                if ($user && $convocation) {
                    // Obtener el programa relacionado con la convocatoria
                    $program = Program::find($convocation->program_id);

                    $requestsData[] = [
                        'id' => $request->id,
                        'status' => $request->status,
                        'user' => $user,
                        'convocation' => $convocation,
                        'program' => $program,
                    ];
                }
            }

            return response()->json($requestsData);
        } catch (\Throwable $th) {
            Log::error('Error al obtener las solicitudes de convocatoria aceptadas para el usuario ' . $userId . ': ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al obtener las solicitudes de convocatoria aceptadas para el usuario ' . $userId
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
