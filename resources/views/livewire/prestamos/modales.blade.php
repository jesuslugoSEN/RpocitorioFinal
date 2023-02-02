<!-- Add Modal -->
<div wire:ignore.self class="modal fade modal-xl" id="finalizarPrestamoModal" data-bs-backdrop="static" tabindex="-1"
    role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content text-white">
            <div class="modal-header text-white bg-primary">
                <h5 class="modal-title" id="createDataModalLabel">Finalizar Prestamo </h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">



                <div class="row">

                    <div class="col-7">

                        <caption>Elementos o Libros Prestados</caption>

                        <div class="table-responsive">
                            <table class="table ">
                                <thead>




                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Novedades</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($datos as $key => $value)
                                <tbody>
                                    <tr class="text-center " wir:key=" {{ $key }} ">
                                        <td scope="row" wire:key=" {{ $key + 1 }} "> {{ $loop->iteration }}
                                        </td>
                                        <td> {{ $value['nombre'] }} </td>
                                        <td> {{ $value['CantidaPrestadaU'] }} </td>
                                        <td> {{ $value['NovedadesPrestamoU'] }} </td>
                                        <td> <button
                                                wire:click.prevent="cargarDatosDevolucionPrestamo(  {{ $key }} )"
                                                class="btn btn-danger  text-white bi bi-dash-circle"></button> Finalizar
                                        </td>
                                    </tr>

                                </tbody>
                                @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>

                    <div class="col-5 ">
                        @include('livewire.prestamos.FormularioDevolucion')
                    </div>

                </div>

                <form>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                <button type="button" wire:click.prevent="store()" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>






<!-- Editar Prestamo Modal -->
<div wire:ignore.self class="modal fade" id="editarPrestamoModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white text-center" id="exampleModalLabel">Editar Prestamo</h5>
                <button type="button" wire:click="cancelar" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form wire:submit.prevent="actualizarPrestamo">

                    <form>

                        <div class="form-group">
                            <label for="Fecha_prestamo">Fecha Prestamo</label>
                            <input title="No Pudes Editar Este Campo" wire:model="Fecha_prestamo" disabled
                                type="text" class="form-control" id="Fecha_prestamo" placeholder="Fecha Prestamo">
                            @error('Fecha_prestamo')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>



                        <div class="form-group">
                            <label for="elementos_id">Elemento o Libro Prestado</label>
                            <input title="No Pudes Editar Este Campo" wire:model="ArticuloPrestado" disabled
                                type="text" class="form-control" id="elementos_id"
                                placeholder="Elemento o Libro Prestado">
                            @error('elementos_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="Usuario">Usuario</label>



                            <input type="text" title="No Pudes Editar Este Campo" wire:model="usuarioDeudor" disabled
                                class="form-control" id="usuariodeudor">
                            </select>

                        </div>


                        <div class="form-group">
                            <label for="elementos_id">Cantidad Prestada</label>
                            <input title="No Pudes Editar Este Campo" @disabled(true) disabled
                                wire:model="CantidadPrestada" type="text" class="form-control" id="elementos_id"
                                placeholder="Elementos Id">
                            @error('elementos_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">

                            <label for="EstadoPrestamo" class="form-label">Estado Del Prestamo</label>
                            <select class="form-select " id="esatdoprestamo" wire:model="Estado_Prestamo">

                                <option value="Activo">Activar</option>
                                <option value="Inactivo">Inactivar</option>

                            </select>

                        </div>


                        @csrf

            </div>
            <div class="modal-footer mt-2">
                <button wire:click="cancelar" type="button" class="btn btn-danger text-white"
                    data-bs-dismiss="modal">Cancelar</button>


                <button type="button" class="btn btn-warning text-white " wire:click="cambiarEstadoPrestamo()"
                    id="liveToastBtn">Actualizar</button>
            </div>
            </form>
        </div>
    </div>
</div>















<!-- Edit Modal -->
<div wire:ignore.self class="modal fade" id="VerDetallesPrestamo" data-bs-backdrop="static" tabindex="-1"
    role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="card mt-0">
                    <h5 class="card-header bg-primary text-white text-center">Elemento Prestado
                        :{{ $detalleElemento }} </h5>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>

                                    <th colspan="3"> Fecha Prestamo:{{ $fechaDetalle }} </th>

                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td colspan="2">Bibliotecario: </td>
                                    <td>{{ $bibliotecario }}</td>

                                </tr>
                                <tr>
                                    <td colspan="2">Cantidad Prestada: </td>
                                    <td>{{ $cantidadPrestadaDetalle }}</td>

                                </tr>
                                <tr>
                                    <td colspan="2">Usuario Deudor: </td>
                                    <td> {{ $nombreDeudor }} {{ $apellidoDeudor }}</td>

                                </tr>
                                <tr>
                                    <td colspan="2">Grado: </td>
                                    <td>{{ $gradoDeudor }}</td>
                                </tr>

                                <tr>
                                    <td colspan="2">Identificaciòn : </td>
                                    <td> {{ $tipoDocDeudor }}: {{ $numeroiDeudor }} </td>
                                </tr>


                                <tr>
                                    <td colspan="2">Celular: </td>
                                    <td> {{ $celularDeudor }} </td>
                                </tr>


                                <tr>
                                    <td colspan="2">Direcciòn: </td>
                                    <td> {{ $direccionDeudor }} </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Estado Prestamo: </td>
                                    <td> {{ $estadoDetalle }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-danger text-white"
                    data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
