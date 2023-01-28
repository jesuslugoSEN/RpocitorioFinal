<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Elemento;
use App\Models\Prestamo;
use App\Models\Categoria;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class Elementos extends Component
{
    use WithPagination;


    public $nombreElemento, $cantidadElemento;
    public $totalCantidad;

    protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $nombre, $cantidad, $descripcion, $Estado, $categoria_id, $name, $Fecha_Prestamo, $usuario_id, $CantidadPrestar, $prestador_id;
    public $arrayElementos = [];
    public function render()
    {
        $consultaUsuarios = User::where('Estado', "=", 'Activo')->select('id', 'name')->get();
        $elementosPrestados = Elemento::where('Estado', "=", 'Prestado')->paginate(10);
        $consulta = Elemento::onlyTrashed()->where('Estado', "=", "Inactivo")->paginate(10);




        $categorias = Categoria::where('Tipo', 'Elementos')->select('id', 'nombre')->get();
        $keyWord = '%' . $this->keyWord . '%';
        return view('livewire.elementos.vistaTabla', [
            'elementos' => Elemento::latest()
                ->orWhere('nombre', 'LIKE', $keyWord)
                ->orWhere('cantidad', 'LIKE', $keyWord)
                ->orWhere('descripcion', 'LIKE', $keyWord)
                ->orWhere('Estado', 'LIKE', $keyWord)
                ->orWhere('categoria_id', 'LIKE', $keyWord)
                ->paginate(10),
        ], compact('categorias', 'consulta', 'elementosPrestados', 'consultaUsuarios'));
    }

    //Funcion Que Limpia los campos Input del formulario
    public function cancelar()
    {
        $this->limpiarCamposInput();
    }



    //Reglas de Validacion
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
    protected $rules = [
        'nombre' => 'required',
        'cantidad' => 'required|min:1|numeric',
        'descripcion' => 'required',
        'Estado' => 'required',
        'categoria_id' => 'required',



    ];
    protected $messages = [
        'cantidad.required' => 'La cantidad es requerida',
        'cantidad.min' => 'La cantidad debe tener al menos  un numero mayor a 0',
        'cantidad.numeric' => 'La cantidad debe ser un numero mayor a 0',
    ];


    public function limpiarCamposInput()
    {
        $this->nombre = null;
        $this->cantidad = null;
        $this->descripcion = null;
        $this->Estado = null;
        $this->categoria_id = null;
    }

    public function crearElemento()
    {

        $validateData = $this->validate();

        Elemento::create([
            'nombre' => $this->nombre,
            'cantidad' => $this->cantidad,
            'descripcion' => $this->descripcion,
            'Estado' => $this->Estado,
            'categoria_id' => $this->categoria_id
        ]);

        $this->cancelar();
        $this->dispatchBrowserEvent('cerrar');
        session()->flash('message', 'Elemento creado Con exito.');

    }

    public function editarElemento($id)
    {
        $record = Elemento::findOrFail($id);
        $this->selected_id = $id;
        $this->nombre = $record->nombre;
        $this->cantidad = $record->cantidad;
        $this->descripcion = $record->descripcion;
        $this->Estado = $record->Estado;
        $this->categoria_id = $record->categoria_id;
    }

    public function actualizarElemento()
    {
        $this->validate([
            'nombre' => 'required',
            'cantidad' => 'required',
            'descripcion' => 'required',
            'Estado' => 'required',
            'categoria_id' => 'required',
        ]);

        if ($this->selected_id) {
            $record = Elemento::find($this->selected_id);
            $record->update([
                'nombre' => $this->nombre,
                'cantidad' => $this->cantidad,
                'descripcion' => $this->descripcion,
                'Estado' => $this->Estado,
                'categoria_id' => $this->categoria_id
            ]);

            $this->cancelar();
            $this->dispatchBrowserEvent('cerrar');
            session()->flash('message', 'Elemento Actualizado Con Exito.');
            $this->resetErrorBag();
        }
    }

    //Inactivar Elemento
    public function inactivarElemento($id)
    {


        $elemento = Elemento::find($id);
        if ($elemento->Estado == 'Disponible') {
            $elemento->Estado = 'Inactivo';
            $elemento->save();
            $elemento->delete();
            session()->flash('message', 'Libro Inactivado Con Exito.');
        } else {
            $elemento->Estado = 'Prestado';

            session()->flash('message', 'Libro No Puede Ser Inactivado Porque actualmente esta prestado.');
        }
    }




    //Restaurar Elemento Eliminado

    public function restaurarElemento($id)
    {
        $resElemento = Elemento::onlyTrashed()->where('id', $id)->first();
        if ($resElemento->Estado == 'Inactivo') {
            $resElemento->Estado = 'Disponible';
            $resElemento->save();
        }
        $resElemento->restore();
        session()->flash('message', 'Elemento Restaurado Con Exito.');
    }


    //Elimina El Registro De La Base De Datos De Manera Definitiva
    public function eliminarElementoTotalMente($id)
    {

        $eliElemento = Elemento::onlyTrashed()->where('id', $id)->first();

        $eliElemento->forceDelete();
        session()->flash('message', 'Elemento Eliminado Del Sistema');
    }

//Funcion Para Cargar Los Datos Del Prestamo
    public function cargarDatosPrestamo($id)
    {



        $prestamoC = Elemento::findOrFail($id);


        if($prestamoC->cantidad==0){
            session()->flash('alertaprestamow', 'No se puede prestar este elemento porque no hay unidades disponibles');
            return;
        }else{
            $prestador = Auth::user()->name;
        $this->name = $prestador;
        $this->prestador_id = Auth::user()->id;
        $this->selected_id = $id;

        $this->nombreElemento = $prestamoC->nombre;
        $this->cantidadElemento = $prestamoC->cantidad;
        $this->descripcion = $prestamoC->descripcion;
        $this->Estado = $prestamoC->Estado;

        session()->flash('alertaprestamow', 'Datos cargados con exito');
        }






    }


    //Funcion Actualizar Cantidad
    public function actualizarCantidad(){

        $elemento = Elemento::find($this->selected_id);
        $CantidadPrestar = $this->CantidadPrestar;
        $cantidadElemento = $this->cantidadElemento;

        $total=$cantidadElemento-$CantidadPrestar;
        $elemento->cantidad=$total;




        $elemento->update();



    }

    public function actualizarEstado(){

        $elemento = Elemento::find($this->selected_id);

        if($elemento->cantidad==0){
            $elemento->Estado='Agotado';

        $elemento->update(); }
    }







//Funcion Para Realizar El Prestamo
    public function realizarPrestamo()
    {

        $CantidadPrestar = $this->CantidadPrestar;
        $cantidadElemento = $this->cantidadElemento;

        if ($CantidadPrestar > $cantidadElemento) {

            session()->flash('alertaprestamow', 'La cantidad a prestar no puede ser mayor a la cantidad del elemento');


        } elseif ($CantidadPrestar <= 0) {
            session()->flash('alertaprestamow', 'La cantidad a prestar no puede ser menor a 0');

        } else {



            if ($this->selected_id) {

                $elemento = Elemento::find($this->selected_id);



                $bi= $prestadorele = Auth::user()->name;


                $tipoel = 'Elemento';

                $arrayElementos = array(

                    'NombreElemento'=>$this->nombreElemento,
                    'id'=>   $this->selected_id,
                    'usuario_id'=>$this->usuario_id,
                    'Tipo_Elemento'=>$tipoel,
                    'NombreBibliotecario'=>$this->name,
                    // $this->nombreLibro = $prestamoLibrof -> Nombre,
                    'CantidadPrestada'=>             $this->CantidadPrestar,




                );


                $this->arrayElementos[] = $arrayElementos;


                $this->actualizarCantidad();

                $this->actualizarEstado();




                session()->flash('alertaprestamow', 'Datos Cargados Con Exito.');
                $this->resetErrorBag();

            }
        }
    }




























    public function quitarElementoPrestamo($key){


        $this->arrayElementos[$key]['id'];

        $idlibor=$this->arrayElementos[$key]['id'];
        $cantidadPrestada=$this->arrayElementos[$key]['CantidadPrestada'];



        $prestamoDevolver = Elemento::findOrFail($idlibor);






        $totaldevLi = (int) $cantidadPrestada +   $cantidadActualParaSumar=$prestamoDevolver->cantidad;


        $prestamoDevolver->cantidad = $totaldevLi;

        $prestamoDevolver->update();


        unset($this->arrayElementos[$key]);
        session()->flash('exito','Prestamo Cancelado');

    }






    public function añadirPrestamoModeloPrestamoElemento(){


        foreach($this->arrayElementos as $key =>$elemento){

            $datos = array(

                "elementos_id" => $elemento['id'],
                "usuario_id" => $elemento['usuario_id'],
                "CantidadPrestada" => $elemento['CantidadPrestada'],
                "Tipo_Elemento" => $elemento['Tipo_Elemento'],
                "NombreBibliotecario" => $elemento['NombreBibliotecario'],
                "created_at"=>now(),
                "updated_at"=>now(),
            );
            Prestamo::insert($datos);
            unset($this->arrayElementos[$key]);
            session()->flash('alertaprestamow', 'Prestamo Realizado  Con Exito.');
            $this->limpiarCampos();
        }
    }










    //Funcion Que Cambia el estado del Elemento
    public function actualizarEstadoLibro(){
        $actualizarEstadoPrestamo = Elemento::find($this->selected_id);


        if($actualizarEstadoPrestamo->cantidad > 0){
            $actualizarEstadoPrestamo->Estado = 'Disponible';


            $actualizarEstadoPrestamo->update();


        }elseif($actualizarEstadoPrestamo->cantidad == 0){
            $actualizarEstadoPrestamo->Estado = 'Agotado';
            $actualizarEstadoPrestamo->update();
        }
    }

    //Funcion Para Limpiar Los Campos
    public function limpiarCampos(){


        $this->name = '';
        $this->cantidadElemento = '';
        $this->nombreElemento = '';
        $this->CantidadPrestar = '';
        $this->Fecha_Prestamo = '';
        $this->usuario_id = '';
        $this->prestador_id = '';
        $this->selected_id = '';
        $this->descripcion = '';
        $this->Estado = '';
        $this->categoria_id = '';


        $this->resetValidation();
    }

}
