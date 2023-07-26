<?php

namespace App\Http\Controllers;

use App\Models\Convocation;
use App\Models\User;
use App\Models\Program;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ConvocationController extends Controller
{
    //
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
    // public function createConvocation(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'program_id' => 'required',
    //             'beginning' => 'required',
    //             'schedule' => 'required'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json($validator->errors(), 400);
    //         }

    //         $validData = $validator->validated();

    //         $userId = auth()->user()->id;

    //         $convocation = Convocation::create([
    //             'program_id' => $validData['program_id'],
    //             'beginning' => $validData['beginning'],
    //             'schedule' => $validData['schedule']
    //         ]);

    //         $convocation->user()->attach($userId, ['owner' => true]);

    //         return response()->json([
    //             'message' => 'Convocation created',
    //             'data' => $convocation
    //         ]);
    //     } catch (\Throwable $th) {
    //         Log::error('Error creating task ' . $th->getMessage());

    //         return response()->json([
    //             'message' => 'Error creating task'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
//     public function updateConvocation(Request $request, $convocationId)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'program_id' => 'required',
//             'beginning' => 'required',
//             'end' => 'required'
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 400);
//         }

//         $validData = $validator->validated();

//         // Buscar la convocatoria existente por su ID
//         $convocation = Convocation::findOrFail($convocationId);

//         // Actualizar los datos de la convocatoria
//         $convocation->update([
//             'program_id' => $validData['program_id'],
//             'beginning' => $validData['beginning'],
//             'end' => $validData['end']
//         ]);

//         // Si se desea cambiar el dueño (owner) de la convocatoria, se puede hacer aquí.
//         // Por ejemplo, si se necesita transferir la propiedad al usuario actualmente autenticado.
//         // Para esto, se pueden agregar líneas similares a las que se usaron en el método createConvocation.

//         return response()->json([
//             'message' => 'Convocation updated',
//             'data' => $convocation
//         ]);
//     } catch (\Throwable $th) {
//         Log::error('Error updating task ' . $th->getMessage());

//         return response()->json([
//             'message' => 'Error updating task'
//         ], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
// }
    // public function joinConvocation(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'id' => 'required'
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json($validator->errors(), 400);
    //         }

    //         $validData = $validator->validated();
    //         $userId = auth()->user()->id;
    //         $convocation = Convocation::find($validData['id']);

    //         // Verificar si el usuario ya está unido a la tarea.
    //         if ($convocation->user->contains($userId)) {
    //             return response()->json([
    //                 'message' => 'User is already joined to the convocation'
    //             ], Response::HTTP_BAD_REQUEST);
    //         }

    //         // En lugar de unir directamente al usuario, se puede agregar una solicitud pendiente.
    //         // Esto requerirá un campo adicional en la tabla pivot, por ejemplo, 'pending' que indica si la solicitud está pendiente o aprobada.
    //         $convocation->user()->attach($userId, ['owner' => false, 'pending' => true]);

    //         return response()->json([
    //             'message' => 'Convocation join request sent',
    //             'data' => $convocation
    //         ]);
    //     } catch (\Throwable $th) {
    //         Log::error('Error joining convocation ' . $th->getMessage());
    //         return response()->json([
    //             'message' => 'Error joining task'
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
};
// User
// es posible que el usuario solicite unirse a la tarea y sea el admin quien apruebe al alumno entra en multitask? 

// Sí, es posible implementar una funcionalidad en la que un usuario solicite unirse a una tarea multitarea, y luego el administrador (o algún rol con permisos especiales) apruebe la solicitud antes de que el usuario pueda unirse oficialmente a la tarea.

// Para lograr esto, puedes realizar algunos cambios en el código existente. Aquí hay una sugerencia de cómo podrías modificar la función joinMultitask para incluir la funcionalidad de aprobación:


//     public function joinMultitask(Request $request)
// {
//     try {
//         $validator = Validator::make($request->all(), [
//             'id' => 'required'
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 400);
//         }

//         $validData = $validator->validated();
//         $userId = auth()->user()->id;
//         $multitask = Multitask::find($validData['id']);

//         // Verificar si el usuario ya está unido a la tarea.
//         if ($multitask->user->contains($userId)) {
//             return response()->json([
//                 'message' => 'User is already joined to the task'
//             ], Response::HTTP_BAD_REQUEST);
//         }

//         // En lugar de unir directamente al usuario, se puede agregar una solicitud pendiente.
//         // Esto requerirá un campo adicional en la tabla pivot, por ejemplo, 'pending' que indica si la solicitud está pendiente o aprobada.
//         $multitask->user()->attach($userId, ['owner' => false, 'pending' => true]);

//         return response()->json([
//             'message' => 'Task join request sent',
//             'data' => $multitask
//         ]);
//     } catch (\Throwable $th) {
//         Log::error('Error joining task ' . $th->getMessage());
//         return response()->json([
//             'message' => 'Error joining task'
//         ], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
// };

// Luego, se puede agregar una nueva función para que el administrador (o el rol con permisos especiales) apruebe las solicitudes de unión. Por ejemplo:

// public function approveJoinRequest(Request $request)
// {
//     try {
//         // Validar los datos recibidos en la solicitud.
//         $validator = Validator::make($request->all(), [
//             'user_id' => 'required',
//             'task_id' => 'required'
//         ]);

//         if ($validator->fails()) {
//             return response()->json($validator->errors(), 400);
//         }

//         $validData = $validator->validated();

//         $user = User::find($validData['user_id']);
//         $multitask = Multitask::find($validData['task_id']);

//         // Verificar si la tarea existe y si el usuario tiene permisos para aprobar solicitudes.
//         if (!$multitask || !$user->isAdmin()) {
//             return response()->json([
//                 'message' => 'Unauthorized'
//             ], Response::HTTP_UNAUTHORIZED);
//         }

//         // Aprobar la solicitud de unión del usuario a la tarea multitarea.
//         // Actualiza el campo 'pending' en la tabla pivot a 'false' para indicar que la solicitud ha sido aprobada.
//         $multitask->user()->updateExistingPivot($user->id, ['pending' => false]);

//         return response()->json([
//             'message' => 'Join request approved for the user',
//             'data' => $multitask
//         ]);
//     } catch (\Throwable $th) {
//         Log::error('Error approving join request ' . $th->getMessage());
//         return response()->json([
//             'message' => 'Error approving join request'
//         ], Response::HTTP_INTERNAL_SERVER_ERROR);
//     }
// }

// Es importante tener en cuenta que la implementación exacta puede variar dependiendo 
// de la estructura de la base de datos y los modelos utilizados en tu aplicación. Además, para agregar la funcionalidad de aprobación, necesitarás definir cómo se manejarán las solicitudes pendientes,
//  ya que esto requerirá cambios en la estructura de la tabla pivot que relaciona a los usuarios con las tareas multitarea.
// 





// public function getAllConvocations()
//     {
//         try {
//             // Paso 1: Obtener el user_id de la solicitud
//             $userId = auth()->user()->id;

//             // Paso 2: Encontrar el usuario por su clave primaria (asumiendo que se usa el modelo User)
//             $user = User::find($userId);

//             // Paso 3: Verificar si el usuario tiene role_id igual a 2 (dentista)
//             if ($user->role_id == 1) {
//                 // Paso 4: Obtener todas las convocatorias cuando el usuario es un dentista
//                 $convocation = Convocation::with(['user', 'program'])
//                     ->get([
//                         'id',
//                         'program_id',
//                         'beginning',
//                         'schedule '
//                     ]);
//             } else {
//                 // Paso 5: Obtener las convocatorias cuando el usuario es un paciente
//                 $convocation = Convocation::with(['user', 'program'])
//                     ->where('user_id', $userId)
//                     ->get([
//                         'id',
//                         'program_id',
//                         'beginning',
//                         'schedule'
//                     ]);
//             }

//             // Paso 6: Devolver la respuesta JSON con los datos
//             return response()->json([
//                 'success' => true,
//                 'message' => 'Todas las convocatorias recuperadas',
//                 'data' => $convocation,
//             ], Response::HTTP_OK);
//         } catch (\Throwable $th) {
//             Log::error('Error al obtener las convocatorias: ' . $th->getMessage());
//             return response()->json([
//                 'message' => 'Error al recuperar las convocatorias'
//             ], Response::HTTP_INTERNAL_SERVER_ERROR);
//         }
//     }