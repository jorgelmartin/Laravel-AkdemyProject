<?php

namespace App\Http\Controllers;

use App\Models\Message;
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
                'user_id' => 'required',
                'convocation_id' => 'required',
                'message' => 'required',
                'date' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $validData = $validator->validated();

            $message = Message::create([
                'user_id' => $validData['user_id'],
                'convocation_id' => $validData['convocation_id'],
                'message' => $validData['message'],
                'date' => $validData['date']
            ]);

            return response()->json([
                'message' => 'Mensaje creado',
                'data' => $message
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al crear el mensaje: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al crear el mensaje'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function editMessage(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'convocation_id' => 'required',
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

            $message->user_id = $validData['user_id'];
            $message->convocation_id = $validData['convocation_id'];
            $message->message = $validData['message'];
            $message->date = $validData['date'];

            $message->save();

            return response()->json([
                'message' => 'Message updated',
                'data' => $message
            ]);
        } catch (\Throwable $th) {
            Log::error('Error updating message ' . $th->getMessage());

            return response()->json([
                'message' => 'Error updating message'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function getAllMessages()
    {
        try {
            $messages = Message::with(['user', 'convocation', 'convocation.program'])->get();

            return response()->json([
                'message' => 'Mensajes obtenidos exitosamente',
                'data' => $messages
            ]);
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

    public function getMessagesByProgram($program_id)
    {
        try {
            $program = Program::find($program_id);

            if (!$program) {
                return response()->json([
                    'message' => 'Programa no encontrado'
                ], Response::HTTP_NOT_FOUND);
            }

            // Accede a las convocatorias relacionadas
            $convocations = $program->convocations;

            // Inicializa un arreglo para almacenar los mensajes
            $messages = [];

            // Bucle for para obtener los mensajes relacionados
            for ($i = 0; $i < count($convocations); $i++) {
                $messages[] = $convocations[$i]->messages;
            }

            // Une todos los mensajes en un solo arreglo
            $allMessages = collect($messages)->flatten();

            return response()->json([
                'message' => 'Mensajes obtenidos exitosamente',
                'data' => $allMessages
            ]);
        } catch (\Throwable $th) {
            Log::error('Error al obtener los mensajes por programa: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error al obtener los mensajes por programa'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
