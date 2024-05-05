<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Program;

class MessageController extends Controller
{
    //
    public function createMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'program_id' => 'required|exists:programs,id',
                'message' => 'required|string|max:1000',
                'date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            // CLEAN MESSAGE AND CONVERT SPECIAL CHARACTERS TO HTML ENTITIES
            $cleanedMessage = htmlspecialchars(strip_tags($validData['message']));

            $message = Message::create([
                'user_id' => $validData['user_id'],
                'program_id' => $validData['program_id'],
                'message' => $cleanedMessage,
                'date' => $validData['date']
            ]);

            return response()->json([
                'message' => 'Mensaje creado',
                'data' => $message
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error al crear el mensaje: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al crear el mensaje'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function messageReply(Request $request, $messageId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'program_id' => 'required|exists:programs,id',
                'message' => 'required|string|max:1000',
                'date' => 'required|date_format:Y-m-d',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $parentMessage = Message::findOrFail($messageId);

            $cleanedMessage = htmlspecialchars(strip_tags($validData['message']));

            $responseMessage = Message::create([
                'user_id' => $validData['user_id'],
                'program_id' => $validData['program_id'],
                'message' => $cleanedMessage,
                'date' => $validData['date'],
                'parent_id' => $messageId,
            ]);

            return response()->json(
                [
                    'message' => 'Respuesta creada con éxito',
                    'data' => $responseMessage
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error('Error al crear la respuesta: ' . $th->getMessage());

            return response()->json(
                [
                    'error' => 'Error interno del servidor',
                    'message' => 'Error al crear la respuesta'
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getAllMessages()
    {
        try {
            $messages = Message::with([
                'user',
                'program',
                'responses',
                'messageParent'
            ])->get();

            return response()->json([
                'message' => 'Mensajes obtenidos exitosamente',
                'data' => $messages
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting all messages: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error getting all messages'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteMessage($id)
    {
        try {
            $message = Message::find($id);

            if (!$message) {
                return response()->json([
                    'message' => 'Message not found'
                ], Response::HTTP_NOT_FOUND);
            }

            $message->delete();

            return response()->json([
                'message' => 'Mensaje borrado exitosamente'
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al eliminar el mensaje: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al eliminar el mensaje'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editMessage(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'program_id' => 'required',
                'message' => 'required',
                'date' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $message = Message::find($id);

            if (!$message) {
                return response()->json([
                    'message' => 'Mensaje no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            if ($message->user_id !== Auth::id()) {
                return response()->json([
                    'message' => 'No tienes permisos para editar este mensaje'
                ], Response::HTTP_FORBIDDEN);
            }

            return response()->json([
                'message' => 'Mensaje actualizado correctamente'
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error al actualizar mensaje: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al actualizar mensaje'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}