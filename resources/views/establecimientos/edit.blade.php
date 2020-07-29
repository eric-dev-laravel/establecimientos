@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin=""/>

    <!-- Esri Leaflet Geocoder -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/esri-leaflet-geocoder/dist/esri-leaflet-geocoder.css"
    />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.1/dropzone.min.css" integrity="sha512-3g+prZHHfmnvE1HBLwUnVuunaPOob7dpksI7/v6UnF/rnKGwHf/GdEq9K7iEN7qTtW+S0iivTcGpeTBqqB04wA==" crossorigin="anonymous" />

@endsection

@section('content')
    <div class="container">

        <h1 class="text-center mt-4">Editar Establecimiento</h1>

        <div class="mt-5 row justify-content-center">

            <form
                class="col-md-9 col-xs-12 card card-body"
                action="{{ route('establecimiento.update', ['establecimiento' => $establecimiento->id]) }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                @method('PUT')
                <fieldset class="border p-4">
                    <legend class="text-primary">Nombre y Categoría e Imagen Principal</legend>

                    <div class="form-group">
                        <label for="nombre">Nombre Establecimiento</label>
                        <input
                            id="nombre"
                            type="text"
                            class="form-control @error('nombre') is-invalid @enderror"
                            placeholder="Nombre Establecimiento"
                            name="nombre"
                            value="{{ $establecimiento->nombre }}"
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <select
                            name="categoria_id"
                            id="categoria"
                            class="form-control @error('categoria_id') is-invalid @enderror"
                        >
                            <option value="" selected disabled>-- Seleccione --</option>

                            @foreach($categorias as $categoria)
                                <option
                                    value="{{ $categoria->id }}"
                                    {{ $establecimiento->categoria_id == $categoria->id ? 'selected' : '' }}
                                >{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>

                        @error('categoria_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="imagen_principal">Imagen Principal</label>
                        <input
                            id="imagen_principal"
                            type="file"
                            class="form-control @error('imagen_principal') is-invalid @enderror"
                            name="imagen_principal"
                            value="{{ old('imagen_principal') }}"
                        >

                        @error('imagen_principal')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                        <img class="mt-3" style="width: 200px" src="/storage/{{$establecimiento->imagen_principal}}" alt="">
                    </div>

                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend class="text-primary">Ubicación</legend>

                    <div class="form-group">
                        <label for="formbuscador">Coloca la dirección de tu establecimiento</label>
                        <input
                            id="formbuscador"
                            type="text"
                            class="form-control"
                            placeholder="Calle del Negocio o Establecimiento"
                        >
                        <p class="text-secondary mt-5 mb-3 text-center">El asistente colocara una dirección estimada o mueve el Pin hacia el lugar correcto.</p>
                    </div>

                    <div class="form-group">
                        <div id="mapa" style="height: 400px"></div>
                    </div>

                    <p class="informacion">Confirma que los siguientes campos son correctos</p>

                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <input
                            id="direccion"
                            type="text"
                            class="form-control @error('direccion') is-invalid @enderror"
                            name="direccion"
                            placeholder="Dirección"
                            value="{{ $establecimiento->direccion }}"
                        >

                        @error('direccion')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="colonia">Colonia</label>
                        <input
                            id="colonia"
                            type="text"
                            class="form-control @error('colonia') is-invalid @enderror"
                            name="colonia"
                            placeholder="Colonia"
                            value="{{ $establecimiento->colonia }}"
                        >

                        @error('colonia')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <input type="hidden" id="lat" name="lat" value="{{ $establecimiento->lat }}">
                    <input type="hidden" id="lng" name="lng" value="{{ $establecimiento->lng }}">

                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend  class="text-primary">Información Establecimiento: </legend>
                    <div class="form-group">
                        <label for="nombre">Teléfono</label>
                        <input
                            type="tel"
                            class="form-control @error('telefono')  is-invalid  @enderror"
                            id="telefono"
                            placeholder="Teléfono Establecimiento"
                            name="telefono"
                            value="{{ $establecimiento->telefono }}"
                        >

                        @error('telefono')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>



                    <div class="form-group">
                        <label for="nombre">Descripción</label>
                        <textarea
                            class="form-control  @error('descripcion')  is-invalid  @enderror"
                            name="descripcion"
                        >{{ $establecimiento->descripcion }}</textarea>

                        @error('descripcion')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre">Hora Apertura:</label>
                        <input
                            type="time"
                            class="form-control @error('apertura')  is-invalid  @enderror"
                            id="apertura"
                            name="apertura"
                            value="{{ $establecimiento->apertura }}"
                        >
                        @error('apertura')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nombre">Hora Cierre:</label>
                        <input
                            type="time"
                            class="form-control @error('cierre')  is-invalid  @enderror"
                            id="cierre"
                            name="cierre"
                            value="{{ $establecimiento->cierre }}"
                        >
                        @error('cierre')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </fieldset>

                <fieldset class="border p-4 mt-5">
                    <legend  class="text-primary">Imagenes Establecimiento: </legend>
                    <div class="form-group">
                        <label for="imagenes">Imagenes</label>
                        <div id="dropzone" class="dropzone form-control"></div>
                    </div>

                    @if(count($imagenes) > 0)
                        @foreach($imagenes as $imagen)
                            <input type="hidden" class="galeria" value="{{$imagen->ruta_imagen}}">
                        @endforeach
                    @endif

                </fieldset>

                <input type="hidden" id="uuid" name="uuid" value="{{ $establecimiento->uuid }}">
                <input type="submit" class="btn btn-primary mt-3 d-block" value="Guardar Cambios">

            </form>

        </div>

    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet" defer></script>
    <script src="https://unpkg.com/esri-leaflet-geocoder" defer></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.1/dropzone.min.js"
        integrity="sha512-Dq6kqg3S+qRPKNx2EHFC+qEHC2TkX8WNFQymh1FxCeurkVEeYknooSSjAFNeLhEZuOl6lfLULnl9kFLJWIn43w=="
        crossorigin="anonymous" defer></script>
@endsection
