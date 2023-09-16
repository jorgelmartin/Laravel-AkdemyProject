<?php

namespace App\Http\Controllers;

// use App\Mail\RequestAccepted;
// use App\Mail\RequestCreated;
use App\Models\Convocation;
use App\Models\Program;
use App\Models\User;
use App\Models\UserConvocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserConvocationController extends Controller
{
    //CREATE USER CONVOCATIONS
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

    //ACCEPT USER REQUEST(ADMIN)
    public function acceptUserRequest(Request $request, $requestId)
    {
        try {
            $convocationRequest = DB::table('user_convocation')
                ->where('id', $requestId)
                ->first();

            if (!$convocationRequest) {
                return response()->json([
                    'message' => 'La solicitud de convocatoria no existe'
                ], Response::HTTP_NOT_FOUND);
            }

            DB::table('user_convocation')
                ->where('id', $requestId)
                ->update(['status' => true]);

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

    //GET MY ACCEPTED REQUEST AS USER
    public function getMyAcceptedUserRequests(Request $request, $userId)
    {
        try {
            $acceptedRequests = DB::table('user_convocation')
                ->where('status', true)
                ->where('user_id', $userId)
                ->get();

            $requestsData = [];
            foreach ($acceptedRequests as $request) {
                $user = User::find($request->user_id);
                $convocation = Convocation::find($request->convocation_id);

                if ($user && $convocation) {
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
    //GET ALL THE REQUEST
    public function getAllInscriptions(Request $request)
    {
        try {
            $allRequests = DB::table('user_convocation')->get();

            //GET INFO FROM USER AND CONVOCATIONS
            $requestsData = [];
            foreach ($allRequests as $request) {
                $user = User::find($request->user_id);
                $convocation = Convocation::find($request->convocation_id);

                if ($user && $convocation) {
                    //GET THE PROGRAM ASSING TO THE CONVOCATION
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
            Log::error('Error al obtener todas las solicitudes de convocatoria: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al obtener todas las solicitudes de convocatoria'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
