<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

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
}
