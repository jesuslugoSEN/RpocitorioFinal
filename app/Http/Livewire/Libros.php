<?php

namespace App\Http\Livewire;

use App\Models\Libro;
use App\Models\Novedades;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;
use Symfony\Component\HttpKernel\HttpCache\Ssi;


class Libros extends Component
{
	use WithPagination;
    private $prestador_id,$Tipo_Elemento;

public $libro , $nombreBibliotecario,$FechaPrestamo, $nombreLibro, $nombreUsuario, $cantidadDisponible, $cantidadPrestamo ,$cantidaPrestarLibro;
	protected $paginationTheme = 'bootstrap';
	public $selected_id,$Cantidad, $buscarLibro, $Nombre, $Autor, $Editorial, $Edicion, $Descripcion, $Estado, $categoria_id,$CantidadLibros, $librosConsulta, $categoriaNombre, $categoriaAutor,$categoriaEditorial ,$categoriaCantidad,$categoriaEstado,$categoriaEdicion,$categoriaDescripcion;
    public $arrayAgregaralatabla = [];

	public function render()
	{
        $buscarLibro = '%'.$this->buscarLibro .'%';
        $consultaUsuariosLibros = User::where('Estado','=','Activo')->select('id','name')->get();
		$consulta = Libro::onlyTrashed()
			->orWhere('Estado', "=", 'Inactivo')
			->paginate(10);
		$categorias = Categoria::where('Tipo', "=", 'Libros')->where('Estado', "=", 'Activa')->select('id', 'nombre')->get()
        ;
        $libros=categoria::select('categorias.nombre','libros.id','libros.Nombre','libros.Autor','libros.Editorial','libros.CantidadLibros','libros.Estado','libros.Edicion')->join('libros','categorias.id','=','libros.categoria_id')

            ->where('libros.Estado','=','Disponible')
            ->orwhere('libros.Estado','=','Agotado')


            ->orderBy('libros.id','desc')
            ->paginate(10)
        ;
        $librosConsulta=categoria::select('categorias.nombre','libros.id','libros.Nombre','libros.Autor','libros.Editorial','libros.CantidadLibros','libros.Estado','libros.Edicion')->join('libros','categorias.id','=','libros.categoria_id')


            ->orWhere('libros.Nombre', 'LIKE', $buscarLibro)
            ->orWhere('categorias.nombre', 'LIKE', $buscarLibro)
            ->orWhere('libros.Autor', 'LIKE', $buscarLibro)
            ->orWhere('libros.Editorial', 'LIKE', $buscarLibro)
            ->orWhere('libros.CantidadLibros', 'LIKE', $buscarLibro)
            ->orWhere('libros.Estado', 'LIKE', $buscarLibro)
            ->orWhere('libros.Edicion', 'LIKE', $buscarLibro)

            ->where('categorias.Tipo','=','Libros')
            ->orwhere('libros.Estado','=','Disponible')


        ->orderBy('libros.id','desc')
        ->paginate(10)
        ;


        return view('livewire.libros.view', compact('categorias','consulta','consultaUsuariosLibros','libros','librosConsulta'));

	}

	public function cancel()
	{
		$this->resetInput();
		$this->resetValidation();
        //->where('categorias.Estado','=','Activa')
	}


/*

		$keyWord = '%' . $this->keyWord . '%';
		return view('livewire.libros.view', [
			'libros' => Libro::latest()
				->orWhere('Nombre', 'LIKE', $keyWord)
				->orWhere('Autor', 'LIKE', $keyWord)
				->orWhere('Editorial', 'LIKE', $keyWord)
				->orWhere('Edicion', 'LIKE', $keyWord)
				->orWhere('Descripcion', 'LIKE', $keyWord)
				->orWhere('Estado', 'LIKE', $keyWord)
				->orWhere('CantidadLibros', 'LIKE', $keyWord)
				->paginate(10),
		], compact('categorias','consulta','consultaUsuariosLibros'));*/
//Validacion de campos
protected $rules = [
    'Nombre' => 'string|required|min:3|max:70',
    'Autor' => 'string|required|min:3|max:70',
    'Editorial' => 'string|required|min:3|max:70',
    'Edicion' => 'string|required|min:3|max:70',
    'Estado' => 'string|required|min:3|max:70',
    'categoria_id' => 'required',
    'Cantidad' => 'numeric|required|min:1',
    'Descripcion' => 'required|max:200|min:3',

];



public function updated($validacionLibros)
    {
        $this->validateOnly($validacionLibros);
    }



    protected $messages = [
        'cantidad.required' => 'The Email Address cannot be empty.',
        'email.email' => 'The Email Address format is not valid.',
    ];



    public function quitarLibroPrestamo($key){


        $this->arrayAgregaralatabla[$key]['id'];

        $idlibor=$this->arrayAgregaralatabla[$key]['id'];
        $cantidadPrestada=$this->arrayAgregaralatabla[$key]['CantidadPrestada'];



        $prestamoDevolver = Libro::findOrFail($idlibor);


        $totaldevLi = (int) $cantidadPrestada +   $cantidadActualParaSumar=$prestamoDevolver->CantidadLibros;;


        $prestamoDevolver->CantidadLibros = $totaldevLi;

        $prestamoDevolver->update();


        unset($this->arrayAgregaralatabla[$key]);
        session()->flash('exito','Prestamo Cancelado');


    }



    public function añadirPrestamoModeloPrestamo(){



        foreach($this->arrayAgregaralatabla as $key =>$libro){

            $datos = array(

                "libros_id" => $libro['id'],
                "usuario_id" => $libro['usuario_id'],
                "CantidadPrestada" => $libro['CantidadPrestada'],
                "Tipo_Elemento" => $libro['Tipo_Elemento'],
                "NombreBibliotecario" => $libro['NombreBibliotecario'],
                "created_at"=>now(),
                "updated_at"=>now(),
            );
            Prestamo::insert($datos);
            unset($this->arrayAgregaralatabla[$key]);
            session()->flash('AlertaPrestamoLibro', 'Prestamo Realizado  Con Exito.');
        }

    }





	private function resetInput()
	{
		$this->Nombre = null;
		$this->Autor = null;
		$this->Editorial = null;
		$this->Edicion = null;
		$this->Descripcion = null;
		$this->Estado = null;
		$this->categoria_id = null;
        $this->Cantidad= null;
	}

	public function store()
	{
		$validarDatos = $this->validate();

		Libro::create([
			'Nombre' => $this->Nombre,
			'Autor' => $this->Autor,
			'Editorial' => $this->Editorial,
			'Edicion' => $this->Edicion,
			'Descripcion' => $this->Descripcion,
			'Estado' => $this->Estado,
			'categoria_id' => $this->categoria_id,
            'CantidadLibros' => $this->Cantidad,


		]);

		$this->resetInput();
		$this->dispatchBrowserEvent('cerrar');
		session()->flash('message', 'Libro Creado Con Exito.');
	}








	public function edit($id)
	{
		$record = Libro::findOrFail($id);
		$this->selected_id = $id;
		$this->Nombre = $record->Nombre;
		$this->Autor = $record->Autor;
		$this->Editorial = $record->Editorial;
		$this->Edicion = $record->Edicion;
		$this->Descripcion = $record->Descripcion;
		$this->Estado = $record->Estado;
		$this->categoria_id = $record->categoria_id;
        $this->CantidadLibros= $record->CantidadLibros;
        $this->Categoria = $record->Categoria;

	}


    public function VerdetalleCategoria($id){

        $vercategoria= Libro::select('libros.Nombre','categorias.nombre','libros.Autor','libros.Editorial','libros.CantidadLibros','libros.Estado','libros.Edicion','libros.Descripcion')->join('categorias','libros.Categoria_id','=','categorias.id')->findOrFail($id);
        $this->categoriaNombre = $vercategoria->Nombre;
        $this->categoriaAutor = $vercategoria->Autor;
        $this->categoriaEditorial = $vercategoria->Editorial;
        $this->categoriaCantidad = $vercategoria->CantidadLibros;
        $this->categoriaEstado = $vercategoria->Estado;
        $this->categoriaEdicion = $vercategoria->Edicion;
        $this->categoriaDescripcion = $vercategoria->Descripcion;


        $categoriaNombre = $vercategoria->nombre;

    }



	public function actualizarLibro()
    {
        $validateData = $this->validate([
            'Nombre' => 'required|min:3|max:70',
            'Autor' => 'required|min:3|max:70',
            'Editorial' => 'required|min:3|max:70',
            'Edicion' => 'required|min:3|max:70',
            'Descripcion' => 'required|min:3|max:200',
            'Estado' => 'required|min:3|max:70',
            'categoria_id' => 'required',
            'CantidadLibros' => 'required|numeric|min:1',
        ]);



        if ($this->selected_id) {
            $record = Libro::find($this->selected_id);
            $record->Nombre = $this->Nombre;
            $record->Autor = $this->Autor;
            $record->Editorial = $this->Editorial;
            $record->Edicion = $this->Edicion;
            $record->Descripcion = $this->Descripcion;
            $record->Estado = $this->Estado;
            $record->categoria_id = $this->categoria_id;
            $record->CantidadLibros = $this->CantidadLibros;
            $this->ValidarCantidad();
            $record->save();
            $this->resetInput();
            $this->dispatchBrowserEvent('cerrar');
            session()->flash('message', 'Libro Actualizado Con Exito.');
            $this->dispatchBrowserEvent('cerrar');
        }
    }

    public function ValidarCantidad(){
        $cantidad = $this->CantidadLibros;

        if ($cantidad <= 0){
            session()->flash('message', 'La cantidad no puede ser menor a 0.');
        }
    }

	public function destroy($id)
    {

        $libro = Libro::find($id);
        $libro->Estado;
        if ($libro->Estado == 'Disponible') {
            $libro->Estado = 'Inactivo';

            $libro->save();
            $libro->delete();

            $this->dispatchBrowserEvent('swal', [
                'title' => 'Categoria Inactivada Con Exito..',
                'icon' => 'info',
                'iconColor' => 'blue',
            ]);
            session()->flash('AlertaPrestamoLibro', 'Libro Inactivado Con Exito.');
        }elseif ($libro->Estado == 'Agotado' and $libro->Estado == 'Prestado'){



        }else{
            session()->flash('AlertaPrestamoLibro', 'Libro No Puede Ser Inactivado Porque actualmente tienen  prestamos realizados.');
        }


    }



	//Restaurar Libro Eliminada

    public function restaurarLibro($id){
        $resLibro =Libro::onlyTrashed()->where('id', $id)->first();
        if($resLibro->Estado == 'Inactivo'){
            $resLibro->Estado = 'Disponible';


            $resLibro->save();
        }
        $resLibro->restore();
        session()->flash('message', 'Libro Restaurado Con Exito.');
    }


        //Elimina El Registro De La Base De Datos De Manera Definitiva
    public function eliminarLibroTotalMente($id){

    $eliLibro =Libro::onlyTrashed()->where('id', $id)->first();

    $eliLibro->forceDelete();
    session()->flash('message','Libro Eliminado Del Sistema');
    }

    public function CargarDatosPrestamosLibros ($id){

        $libro = Libro::find($id);
        $Consulta = Novedades::select('libros.id','libros.Nombre','novedades.Tipo_novedad','novedades.id','novedades.id_libros')
            -> leftjoin('libros','novedades.id_libros','=','libros.id')->where('novedades.id_libros', '=', $id)
            ->orderBy('novedades.id', 'desc')->first();
        if($Consulta->Tipo_novedad == 'Baja' ){
            if ($libro->Estado =='Disponible' ) {

                $prestamoLibrof = Libro::findOrFail($id);
                $prestadorLibro = Auth::user()->name;
                $this->nombreBibliotecario=$prestadorLibro;
                $this->prestador_id = Auth::user()->id;
                $this->selected_id = $id;
                $this->nombreLibro = $prestamoLibrof -> Nombre;
                $this->cantidadDisponible = $prestamoLibrof -> CantidadLibros;
                session()->flash('AlertaPrestamoLibro', 'Datos Cargados Con Exito.');

            } else {

                session()->flash('AlertaPrestamoLibro', 'El Libro No Esta Disponible.');

            }
        }



    }






    public function ActualizarCantidadLibros (){
        $librof = Libro::find($this->selected_id);

        $cantidaPrestarLibro = $this->cantidadPrestamo;
        $cantidadDisponible = $this-> cantidadDisponible;
        $Total = $cantidadDisponible-$cantidaPrestarLibro;
        $librof->CantidadLibros = $Total;
        $librof->update();
    }

    public function RealizarPrestamoLibro(){






        $validateData = $this->validate([
            'cantidadPrestamo' => 'required|numeric',
            'nombreUsuario'=>'required',
            'cantidadDisponible'=>'required',
            'nombreBibliotecario'=>'required',
            'nombreLibro'=>'required',


        ]);



        $cantidaPrestarLibro = $this->cantidadPrestamo;
        $cantidadDisponible = $this-> cantidadDisponible;
        if($cantidaPrestarLibro > $cantidadDisponible){
            session()->flash('AlertaPrestamoLibro','La cantidad aprestar no puede ser mayor a la cantidad de libros disponbles');
        }elseif ($cantidaPrestarLibro <= 0){
            session()->flash('AlertaPrestamoLibro','La cantidad aprestar tiene que ser mayor a 0');
        }else{
            if ($this->selected_id){


                $prestamoLibrof = Libro::findOrFail($this->selected_id);
                $tipoel = 'Libro';


                $arrayAgregaralatabla = array(
                    'Fecha_prestamo'=>$this->FechaPrestamo,
                    'NombreLibro '=>$this->nombreLibro,
                    'id'=>   $this->selected_id,
                    'usuario_id'=>$this->nombreUsuario,
                    'Tipo_Elemento'=>$tipoel,
                    'NombreBibliotecario'=>$this->nombreBibliotecario,
                    // $this->nombreLibro = $prestamoLibrof -> Nombre,
                    'CantidadPrestada'=>             $this->cantidadPrestamo,




                );

                $this->ActualizarCantidadLibros();
                $this->actualizarEstadoLibro();
                $this->limpiarCamposPrestamo();
                $this->arrayAgregaralatabla[] = $arrayAgregaralatabla;




                /*






                                $PrestamoRealizado->save();
                                session()->flash('AlertaPrestamoLibro','Prestamo Realizado con Exito');

                                */
            }
        }



    }



public function limpiarCamposPrestamo()
    {


		$this->cantidadPrestamo = null;
		$this->nombreLibro = null;
		$this->cantidadDisponible= null;
		$this->nombreBibliotecario= null;
		$this->FechaPrestamo = null;


    }

    public function actualizarEstadoLibro(){
        $librof = Libro::find($this->selected_id);
        if($librof->CantidadLibros > 0){
            $librof->Estado = 'Disponible';
            $librof->update();

        }elseif($librof->CantidadLibros == 0){
            $librof->Estado = 'Agotado';
            $librof->update();
        }
    }



 }
