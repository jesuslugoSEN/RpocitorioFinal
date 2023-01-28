<?php

namespace App\Http\Livewire;

use App\Models\Libro;
use Livewire\Component;
use App\Models\Elemento;
use App\Models\Prestamo;
use App\Models\Novedades;
use App\Models\Devolucion;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

class Prestamos extends Component
{
    use WithPagination;

    protected $listeners = ['render' => 'render'];

    protected $paginationTheme = 'bootstrap';
    public $prestamos_id, $CantidadPrestamo, $Estado_Prestamo, $cambiarEstado;
    public $selected_id, $buscadorPrestamos, $Fecha_prestamo, $libros_id, $elementos_id, $usuario_id, $curso_id, $CantidadPrestada, $ArticuloPrestado;
    public $usuarioDeudor, $prestador_id, $bibliotecario, $articuloDevolver, $CantidadPrestadaDevolver, $usuarioDeudorD, $NovedadesDevolucion, $CantidadDevuelta;

    public $detalleElemento, $cantidadPrestadaDetalle, $fechaDetalle, $nombreDeudor, $apellidoDeudor, $gradoDeudor, $numeroiDeudor, $tipoDocDeudor, $celularDeudor, $direccionDeudor, $estadoDetalle,$Tipo_novedad;



    public function render()
    {
        $prestamosEliminados = Prestamo::onlyTrashed()->where('Estado_Prestamo', 'Inactivo')->paginate(10);

        $consultaUsuariosPrestamos = User::where('Estado', "=", 'Activo')->select('id', 'name')->get();

        $buscadorPrestamos = '%' . $this->buscadorPrestamos . '%';

        $consultaLibrosElementos = Prestamo::select('prestamos.*', 'libros.id', 'libros.Nombre', 'elementos.id', 'users.id', 'users.name', 'elementos.nombre', 'prestamos.id')
            ->leftJoin('libros', 'libros.id', '=', 'prestamos.libros_id')
            ->leftJoin('elementos', 'elementos.id', '=', 'prestamos.elementos_id')
            ->leftJoin('users', 'users.id', '=', 'prestamos.usuario_id')
            ->orderBy('prestamos.id', 'desc')
            ->where('Estado_Prestamo', "=", 'Activo')
            ->orWhere('libros.Nombre', 'LIKE', $buscadorPrestamos)
            ->orWhere('users.name', 'LIKE', $buscadorPrestamos)
            ->orWhere('elementos.nombre', 'LIKE', $buscadorPrestamos)

            ->paginate(10)
        ;







        return view('livewire.prestamos.vistaprestamos', [
            'prestamos' => Prestamo::latest()
                ->orWhere('libros_id', 'LIKE', $buscadorPrestamos)
                ->orWhere('elementos_id', 'LIKE', $buscadorPrestamos)
                ->orWhere('usuario_id', 'LIKE', $buscadorPrestamos)

                ->paginate(8),
        ], compact('consultaUsuariosPrestamos', 'prestamosEliminados', 'consultaLibrosElementos'));




    }
    /*
    $prestamos = Prestamo::select('prestamos.id','elementos.*','libros.*')

    ->join('elementos','elementos.id','=','prestamos.elementos_id')
    ->join('libros','libros.id','=','prestamos.libros_id')
    ->get();
    return view('livewire.prestamos.vistaprestamos', [
    'prestamos' => Prestamo::latest()
    ->orWhere('Fecha_prestamo', 'LIKE', $buscadorPrestamos)
    ->orWhere('libros_id', 'LIKE', $buscadorPrestamos)
    ->orWhere('elementos_id', 'LIKE', $buscadorPrestamos)
    ->orWhere('usuario_id', 'LIKE', $buscadorPrestamos)
    ->orWhere('curso_id', 'LIKE', $buscadorPrestamos)
    ->paginate(8),
    ],compact('consultaUsuariosPrestamos','prestamosEliminados'));


    $prestamos = Prestamo::select('prestamos.id','prestamos.CantidadPrestada','prestamos.Fecha_prestamo','prestamos.Estado_Prestamo','prestamos.elementos_id','prestamos.Fecha_prestamo','prestamos.Estado_Prestamo','prestamos.usuario_id','prestamos.elementos_id','prestamos.prestador_id','elementos.nombre','libros.Nombre')
    ->join('elementos','elementos.id','=','prestamos.elementos_id')
    ->join('libros','libros.id','=','prestamos.libros_id')
    ->where('prestamos.Estado_Prestamo','=','Activo')
    ->orderBy('prestamos.id','desc')
    ->orWhere('Fecha_prestamo','LIKE','%'.$this->buscadorPrestamos)



    ->orWhere('CantidadPrestada','LIKE','%'.$this->buscadorPrestamos.'%')


    ->paginate(5);*/


    public function cancel()
    {
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->Fecha_prestamo = null;
        $this->libros_id = null;
        $this->elementos_id = null;
        $this->usuario_id = null;

    }

    public function store()
    {
        $this->validate([
            'Fecha_prestamo' => 'required',
            'libros_id' => 'required',
            'elementos_id' => 'required',
            'usuario_id' => 'required',

        ]);

        Prestamo::create([
            'Fecha_prestamo' => $this->Fecha_prestamo,
            'libros_id' => $this->libros_id,
            'elementos_id' => $this->elementos_id,
            'usuario_id' => $this->usuario_id,

        ]);

        $this->resetInput();
        $this->dispatchBrowserEvent('closeModal');
        session()->flash('message', 'Prestamo Successfully created.');
    }

    public function editarPrestamo($id)
    {


        $editarPrestamo = Prestamo::findOrFail($id);

        if ($editarPrestamo->Estado_Prestamo == 'Finalizado') {

            $this->dispatchBrowserEvent('cerrar');
            session()->flash('error', 'No se puede editar un prestamo finalizado');
            return;
        }


        $this->libros_id = null;

        $this->selected_id = $id;


        if ($editarPrestamo->libros_id == null) {
            $this->libros_id = null;

        } else {
            $usuario = User::findOrFail($editarPrestamo->usuario_id);


            $libro = Libro::findOrFail($editarPrestamo->libros_id);
            $this->libros_id = $editarPrestamo->libros_id;



            $this->usuario_id = $editarPrestamo->usuario_id;
            $this->usuarioDeudor = $usuario->name;
            $this->ArticuloPrestado = $libro->Nombre;

        }


        if ($editarPrestamo->elementos_id == null) {
            $this->elementos_id = null;


        } else {
            $usuario = User::findOrFail($editarPrestamo->usuario_id);

            $elemento = Elemento::findOrFail($editarPrestamo->elementos_id);

            $this->elementos_id = $editarPrestamo->elementos_id;

            $this->usuario_id = $editarPrestamo->usuario_id;
            $this->usuarioDeudor = $usuario->name;
            $this->ArticuloPrestado = $elemento->nombre;
        }








        $estadoInicial = $editarPrestamo->Estado_Prestamo;

        $this->Estado_Prestamo = $estadoInicial;

        $this->CantidadPrestada = $editarPrestamo->CantidadPrestada;

        $this->Fecha_prestamo = $editarPrestamo->Fecha_prestamo;

    }




    public function cambiarEstadoPrestamo()
    {





        $prestamo = Prestamo::find($this->selected_id);

        $this->inactivarPrestamo($this->selected_id);
        $this->dispatchBrowserEvent('cerrar')

        ;

        session()->flash('message', 'Prestamo Inactiavado');
    }



    //Eliminar De Manera Temporal Prestamo
    public function inactivarPrestamo($id)
    {

        $prestamoEli = Prestamo::find($id);

        if ($prestamoEli->Estado_Prestamo == 'Activo' or $prestamoEli->Estado_Prestamo == 'Finalizado') {
            $prestamoEli->Estado_Prestamo = 'Inactivo';


            $prestamoEli->save();
            $prestamoEli->delete();



            $this->emit('render')
            ;

        }


    }




    public function cargarDatosDevolucionPrestamo($id )
    {

        $consultaGeneral = Prestamo::select('prestamos.*', 'elementos.*','libros.*','libros.id', 'libros.Nombre', 'elementos.id', 'users.id', 'users.name', 'elementos.nombre', 'prestamos.id')
        ->leftJoin('libros', 'libros.id', '=', 'prestamos.libros_id')
        ->leftJoin('elementos', 'elementos.id', '=', 'prestamos.elementos_id')
        ->leftJoin('users', 'users.id', '=', 'prestamos.usuario_id')
        ->orderBy('prestamos.id', 'desc')
        ->where('Estado_Prestamo', "=", 'Activo')
    ->find($id);







if ($consultaGeneral->Tipo_Elemento == 'Libro') {
            $this->libros_id = $consultaGeneral->libros_id;
            $this->elementos_id = null;

            $prestador = Auth::user()->name;
            $this->bibliotecario = $prestador;
            $this->prestador_id = Auth::user()->id;
            $this->usuarioDeudorD= $consultaGeneral->name;
            $this->articuloDevolver= $consultaGeneral->Nombre;
            $this->CantidadPrestadaDevolver= $consultaGeneral->CantidadPrestada;
            $this->prestamos_id = $consultaGeneral->id;
        $this->libros_id = $consultaGeneral->libros_id;
        $this->usuario_id=$consultaGeneral->usuario_id;
        $this->selected_id=$consultaGeneral->id;

        }elseif ($consultaGeneral->Tipo_Elemento == 'Elemento') {
            $this->elementos_id = $consultaGeneral->elementos_id;
            $this->libros_id = null;
            $prestador = Auth::user()->name;
            $this->bibliotecario = $prestador;
            $this->prestador_id = Auth::user()->id;
            $this->usuarioDeudorD= $consultaGeneral->name;
            $this->articuloDevolver= $consultaGeneral->nombre;
            $this->CantidadPrestadaDevolver= $consultaGeneral->CantidadPrestada;
            $this->prestamos_id = $consultaGeneral->id;
        $this->elementos_id = $consultaGeneral->elementos_id;
        $this->usuario_id=$consultaGeneral->usuario_id;
        $this->selected_id = $consultaGeneral->id;

        }













/*
$elemento = Elemento::findOrFail($cargarDatosDevolucion->elementos_id);
        $usuario = User::findOrFail($cargarDatosDevolucion->usuario_id);
        $prestador = Auth::user()->name;
        $this->bibliotecario = $prestador;
        $this->prestador_id = Auth::user()->id;
*/
    }
    //Finalizar Prestamo

    public function finalizarPrestamoEstado($id)
    {
        $prestamoFinalizarCambiarEstado = Prestamo::find($id);
        if ($prestamoFinalizarCambiarEstado->Estado == 'Activo') {
            $prestamoFinalizarCambiarEstado->Estado = 'Finalizado';
            $prestamoFinalizarCambiarEstado->save();
            $prestamoFinalizarCambiarEstado->delete();




        }
    }

    public function actualizarCantidadElementosDevolucion()
    {



        $elementoDevo = Elemento::findOrFail($this->elementos_id);

        // 0

        //250
        $CantidadDevuelta = $this->CantidadDevuelta;


        $total = $CantidadDevuelta + $elementoDevo->cantidad;

        $elementoDevo->cantidad = $total;

        $elementoDevo->Estado = 'Disponible';

        $elementoDevo->update();





        session()->flash('mensajedevo', 'Cantidad Devuelta Con Exito .....');
    }


    public function actualizarCantidadLibrosDevolucion()
    {



        $libroDevo = Libro::findOrFail($this->libros_id);

        // 0

        //250
        $CantidadDevuelta = $this->CantidadDevuelta;


        $total = $CantidadDevuelta + $libroDevo->CantidadLibros;

        $libroDevo->CantidadLibros = $total;



        $libroDevo->update();

        session()->flash('mensajedevo', 'Cantidad Devuelta Con Exito .....');
    }
    public function actualizarCantidadDevolucionLibros()
    {

        $prestamo = Prestamo::select('prestamos.*', 'elementos.*','libros.*','libros.id', 'libros.Nombre', 'elementos.id', 'users.id', 'users.name', 'elementos.nombre', 'prestamos.id')
        ->leftJoin('libros', 'libros.id', '=', 'prestamos.libros_id')
        ->leftJoin('elementos', 'elementos.id', '=', 'prestamos.elementos_id')
        ->leftJoin('users', 'users.id', '=', 'prestamos.usuario_id')
        ->orderBy('prestamos.id', 'desc')
        ->where('Estado_Prestamo', "=", 'Activo')


            ->where('prestamos.id', '=', $this->selected_id)
            ->first()
            ->findOrFail($this->selected_id);




        $CantidadDevuelta = $this->CantidadDevuelta;

        $cantidadActual = $prestamo->CantidadPrestada;

        if ($CantidadDevuelta > $cantidadActual) {
            session()->flash('mensajedevo', 'La Cantidad Devuelta No Puede Ser Mayor A La Cantidad Prestada .....');

        } elseif ($CantidadDevuelta == 0) {
            session()->flash('mensajedevo', 'La Cantidad Devuelta No Puede Ser menor o igual a 0 .....');
        } else {


            $total = $cantidadActual - $CantidadDevuelta;


            if ($total == 0) {
                $prestamo->Estado_Prestamo = 'Finalizado';
                session()->flash('mensajedevo', 'Prestamo Finalizado Con exito.......');
            }

            $prestamo->CantidadPrestada = $total;



            $this->actualizarCantidadLibrosDevolucion();


            $prestamo->update();



            $prestamo->save();
            $this->cancelarDevolucion();
        }




    }




    public function actualizarCantidadDevolucion()
    {

        $prestamo = Prestamo::select('prestamos.*', 'elementos.*','libros.*','libros.id', 'libros.Nombre', 'elementos.id', 'users.id', 'users.name', 'elementos.nombre', 'prestamos.id')
        ->leftJoin('libros', 'libros.id', '=', 'prestamos.libros_id')
        ->leftJoin('elementos', 'elementos.id', '=', 'prestamos.elementos_id')
        ->leftJoin('users', 'users.id', '=', 'prestamos.usuario_id')
        ->orderBy('prestamos.id', 'desc')
        ->where('Estado_Prestamo', "=", 'Activo')


            ->where('prestamos.id', '=', $this->selected_id)
            ->first()
            ->findOrFail($this->selected_id);




        $CantidadDevuelta = $this->CantidadDevuelta;

        $cantidadActual = $prestamo->CantidadPrestada;

        if ($CantidadDevuelta > $cantidadActual) {
            session()->flash('mensajedevo', 'La Cantidad Devuelta No Puede Ser Mayor A La Cantidad Prestada .....');

        } elseif ($CantidadDevuelta == 0) {
            session()->flash('mensajedevo', 'La Cantidad Devuelta No Puede Ser menor o igual a 0 .....');
        } else {


            $total = $cantidadActual - $CantidadDevuelta;


            if ($total == 0) {
                $prestamo->Estado_Prestamo = 'Finalizado';
                session()->flash('mensajedevo', 'Prestamo Finalizado Con exito.......');
            }

            $prestamo->CantidadPrestada = $total;



            $this->actualizarCantidadElementosDevolucion();


            $prestamo->update();



            $prestamo->save();
            $this->cancelarDevolucion();
        }




    }

    public function enviarDatosDevolucion()
    {
$this->selected_id = $this->prestamos_id;


$elementoPrestar = Prestamo::findOrFail($this->prestamos_id);









        $this->validate([

           'usuario_id'=>'required'


        ]);






        if ($this->selected_id) {




           if($elementoPrestar->Tipo_Elemento =='Libro'){


            $finalizarPrestamo = new Devolucion();

            $finalizarPrestamo->elementos_id = null;
            $finalizarPrestamo->Cantidad_Devuelta = $this->CantidadDevuelta;
            $finalizarPrestamo->libros_id = $this->libros_id;


            $finalizarPrestamo->usuario_id = $this->usuario_id;
            $finalizarPrestamo->prestamos_id = $this->selected_id;


            $finalizarPrestamo->prestador_id = Auth::user()->id;
            $finalizarPrestamo->Bibliotecario_Re = Auth::user()->name;

          $this->actualizarCantidadDevolucionLibros();
           $Cargarnovedad = new Novedades();
           $Cargarnovedad->Novedades = $this->NovedadesDevolucion;
           $Cargarnovedad -> id_libros = $this->libros_id;
           $Cargarnovedad -> Tipo_novedad = $this->Tipo_novedad;
           $Cargarnovedad -> save();
           $finalizarPrestamo->save();

        }elseif($elementoPrestar->Tipo_Elemento =='Elemento'){


            $finalizarPrestamo = new Devolucion();

            $finalizarPrestamo->elementos_id = $this->elementos_id;
            $finalizarPrestamo->Cantidad_Devuelta = $this->CantidadDevuelta;
            $finalizarPrestamo->libros_id = null;


            $finalizarPrestamo->usuario_id = $this->usuario_id;
            $finalizarPrestamo->prestamos_id = $this->selected_id;

            $finalizarPrestamo->Novedades = $this->NovedadesDevolucion;
            $finalizarPrestamo->prestador_id = Auth::user()->id;
            $finalizarPrestamo->Bibliotecario_Re = Auth::user()->name;


            $this->actualizarCantidadDevolucion();
            $finalizarPrestamo->save();
        }
else{
    session()->flash('mensajedevo', 'No se puede realizar la devolucion.....');
}




        }








    }























    //Funcion Limpiar Campos ----------------------------------------------------------------------------------------
    public function cancelarDevolucion()
    {

        $this->NovedadesDevolucion = '';
        $this->CantidadDevuelta = '';
        $this->elementos_id = '';
        $this->usuario_id = '';
        $this->prestamos_id = '';
        $this->prestador_id = '';
        $this->selected_id = '';
        $this->CantidadPrestadaDevolver = '';

        $this->bibliotecario = '';
        $this->prestador_id = '';




        $this->articuloDevolver = '';


        $this->Fecha_prestamo = '';

        $this->usuarioDeudorD = '';


    }












































































    //Funcion de Ver Detalles Del Prestamo -------------------------------------------------
    public function verDetallesPrestamo($id)
    {


        $consulta = Prestamo::select('prestamos.*', 'libros.*', 'elementos.*', 'users.*', 'libros.Nombre', 'elementos.id', 'users.id', 'users.name', 'elementos.nombre', 'prestamos.id')
            ->leftJoin('libros', 'libros.id', '=', 'prestamos.libros_id')
            ->leftJoin('elementos', 'elementos.id', '=', 'prestamos.elementos_id')
            ->leftJoin('users', 'users.id', '=', 'prestamos.usuario_id')
            ->orderBy('prestamos.id', 'desc')
            ->where('prestamos.id', '=', $id)
            ->first();


        if ($consulta->libros_id != null) {




            $this->prestador_id = Auth::user()->id;

            $this->bibliotecario = Auth::user()->name;

            $this->detalleElemento = $consulta->Nombre;



            $this->fechaDetalle = $consulta->Fecha_prestamo;
            $this->cantidadPrestadaDetalle = $consulta->CantidadPrestada;
            $this->nombreDeudor = $consulta->Name;
            $this->apellidoDeudor = $consulta->lastname;
            $this->gradoDeudor = $consulta->Grado;
            $this->numeroiDeudor = $consulta->NumeroDoc;
            $this->tipoDocDeudor = $consulta->TipoDoc;
            $this->celularDeudor = $consulta->celular;
            $this->direccionDeudor = $consulta->direccion;
            $this->estadoDetalle = $consulta->Estado_Prestamo;
        } else {

            $this->prestador_id = Auth::user()->id;

            $this->bibliotecario = Auth::user()->name;

            $this->detalleElemento = $consulta->nombre;

            $this->fechaDetalle = $consulta->Fecha_prestamo;
            $this->cantidadPrestadaDetalle = $consulta->CantidadPrestada;
            $this->nombreDeudor = $consulta->name;
            $this->apellidoDeudor = $consulta->lastname;
            $this->gradoDeudor = $consulta->Grado;
            $this->numeroiDeudor = $consulta->NumeroDoc;
            $this->tipoDocDeudor = $consulta->TipoDoc;
            $this->celularDeudor = $consulta->celular;
            $this->direccionDeudor = $consulta->direccion;
            $this->estadoDetalle = $consulta->Estado_Prestamo;


        }


    }
}
