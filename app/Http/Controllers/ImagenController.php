<?php

namespace App\Http\Controllers;

use App\Establecimiento;
use App\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Leer la imagen
        $ruta_imagen = $request->file('file')->store('establecimiento', 'public');

        //Resize a la imagen
        $imagen = Image::make(public_path("storage/{$ruta_imagen}"))->fit(800, 450);
        $imagen->save();

        //Almacenar con modelo
        $imagenDB = new Imagen;
        $imagenDB->id_establecimiento = $request['uuid'];
        $imagenDB->ruta_imagen = $ruta_imagen;
        $imagenDB->save();

        //Retornar una respuesta
        $respuesta = [
            'archivo' => $ruta_imagen
        ];

        return response()->json($respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Imagen  $imagen
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request )
    {
        $uuid = $request->get('uuid');
        $establecimiento = Establecimiento::where('uuid', $uuid)->first();
        $this->authorize('delete', $establecimiento);

        $imagen = $request->get('imagen');

        if(File::exists('storage/' . $imagen)){
            //Elimina imagen del server
            File::delete('storage/' . $imagen);

            //Elimina imagen dela BD
            //$imagenEliminar = Imagen::where('ruta_imagen', '=', $imagen)->firstOrFail();
            //Imagen::destroy($imagenEliminar->id);
            Imagen::where('ruta_imagen', $imagen)->delete();
        }

        $respuesta = [
            'mensaje' => 'Imagen eliminada',
            'imagen' => $imagen
        ];

        return response()->json($respuesta);
    }
}
