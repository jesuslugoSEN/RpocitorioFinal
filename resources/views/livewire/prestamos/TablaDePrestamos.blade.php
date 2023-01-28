



<div class="card  mt-3 ">

    <div class="card-header d-flex justify-content-between bg-white">
        <h4 class="text-center ">Gestiòn De Prestamos</h4>

    </div>

    <div class="d-flex  justify-content-between">

        <div class="col-6">
            <input wire:model='buscadorPrestamos' type="text" class="form-control  m-3" name="buscarPrestamo"
                id="buscarPrestamo" placeholder="Buscar Prestamo...">
        </div>



    </div>


    <div class="card-body  ">

        <div class="table-responsive">
            <table class="table libros table-bordered table-sm">
                <thead class="thead">
                    <tr>
                        <td>#</td>
                        <th>Bibliotecario</th>
                        <th>Fecha Prestamo</th>
                        <th>Elemento o Libro Prestado</th>

                        <th>Usuario </th>
                        <th>Cantidad </th>
                        <th>Estado</th>

                        <td>Acciones</td>

                    </tr>
                </thead>
                <tbody>
                    @forelse($consultaLibrosElementos as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->NombreBibliotecario }}</td>

                            <td>{{ $row->Fecha_prestamo }}</td>





                            @if ($row->Tipo_Elemento == 'Libro')
                                <td>{{ $row->Nombre }}</td>
                            @else
                                <td>{{ $row->nombre }}</td>
                            @endif



                            <td>{{ $row->name }}</td>




                            <td colspan="">{{ $row->CantidadPrestada }}</td>
                            @if ($row->Estado_Prestamo == 'Activo')
                                <td class=" text-white">
                                    <button title="Activo"
                                        class="btn btn-warning m-1 bi bi bi-check2-square btn-sm text-white">

                                    </button>


                                </td>
                            @else
                                <td class=" text-white" title="Finalizado">
                                    <button class="btn btn-dark m-1 bi bi-x-octagon-fill btn-sm text-white">

                                    </button>


                                </td>
                            @endif


                            <td colspan="3" class="d-flex justify-content-around">


                                <button title="Editar" data-bs-toggle="modal" data-bs-target="#editarPrestamoModal"
                                    class=" bi bi-pencil-square m-1 btn-sm text-white btn btn-info "
                                    wire:click="editarPrestamo({{ $row->id }})"> </button>


                                <a title="Inactivar" class="btn m-1 btn-danger bi bi-trash3-fill btn-sm  text-white "
                                    onclick="confirm('Desea inactivar el prestamo {{ $row->id }}? \nSi No!')||event.stopImmediatePropagation()"
                                    wire:click="inactivarPrestamo({{ $row->id }})"></a>
                                <a title="Ver Detalles" data-bs-toggle="modal" data-bs-target="#VerDetallesPrestamo"
                                    class=" bi bi bi-eye-fill m-1 btn-sm text-white btn btn-warning "
                                    wire:click="verDetallesPrestamo({{ $row->id }})"> </a>

                                <a title="Finalizar Prestamo" data-bs-toggle="modal"
                                    data-bs-target="#verDetallesCategoria"
                                    class=" bi bi-clock-fill m-1 btn-sm text-white btn btn-primary "
                                    wire:click="cargarDatosDevolucionPrestamo({{ $row->id }})"> </a>




                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td class="text-center bg-emerald-300" colspan="100%">No hay registros para mostrar</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="float-end">{{ $prestamos->links() }}</div>
        </div>
    </div>





</div>
