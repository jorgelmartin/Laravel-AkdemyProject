<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends Controller
{
    //
    public function getAllPrograms()
    {
        try {
            $services = Program::get();
            return response()->json([
                'message' => 'Programs retrieved',
                'data' => $services
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error getting programs ' .
                $th->getMessage());
            return response()->json([
                'message' => 'Error retrieving programs'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    // public function show(Program $program)
    // {
    //     // Obtener el nombre de la imagen asociada al programa
    //     $imageName = $program->images;

    //     // Pasar el nombre de la imagen a la vista y mostrar la vista
    //     return view('program.show', compact('program', 'imageName'));
    // }
    public function store(Request $request)
{
    // Validar el formulario y otros campos (nombre, descripción, precio, etc.)

    // Procesar la imagen cargada
    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $uploadedImage = $request->file('image');
        $path = $uploadedImage->store('public/images'); // Guardar la imagen en la carpeta 'public/program_images'

        // Guardar la ruta de la imagen en la base de datos
        $program = new Program();
        $program->name = $request->input('name');
        $program->description = $request->input('description');
        $program->price = $request->input('price');

        // Ajustar la ruta para que sea relativa a la carpeta public
        $program->image = 'images/' . $uploadedImage->hashName();

        $program->save();

        return redirect()->route('program.show', $program->id)
            ->with('success', '¡Programa creado exitosamente!');
    }

    // Si algo falla, regresar al formulario con los datos anteriores y mostrar un mensaje de error.
    return back()->withErrors(['image' => 'Error al cargar la imagen']);
}
}
