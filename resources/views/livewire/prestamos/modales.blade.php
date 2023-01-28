<!-- Add Modal -->
<div wire:ignore.self class="modal fade" id="crearNuevoPrestamoModal" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Create New Prestamo</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <div class="modal-body">
				<form>
                    <div class="form-group">
                        <label for="Fecha_prestamo"></label>
                        <input wire:model="Fecha_prestamo" type="text" class="form-control" id="Fecha_prestamo" placeholder="Fecha Prestamo">@error('Fecha_prestamo') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="libros_id"></label>
                        <input wire:model="libros_id" type="text" class="form-control" id="libros_id" placeholder="Libros Id">@error('libros_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="elementos_id"></label>
                        <input wire:model="elementos_id" type="text" class="form-control" id="elementos_id" placeholder="Elementos Id">@error('elementos_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="usuario_id"></label>
                        <input wire:model="usuario_id" type="text" class="form-control" id="usuario_id" placeholder="Usuario Id">@error('usuario_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="curso_id"></label>
                        <input wire:model="curso_id" type="text" class="form-control" id="curso_id" placeholder="Curso Id">@error('curso_id') <span class="error text-danger">{{ $message }}</span> @enderror
                    </div>

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
<div wire:ignore.self class="modal fade" id="editarPrestamoModal" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                            <input  title="No Pudes Editar Este Campo" wire:model="Fecha_prestamo" disabled type="text" class="form-control" id="Fecha_prestamo" placeholder="Fecha Prestamo">@error('Fecha_prestamo') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       
                       

                        <div class="form-group">
                            <label for="elementos_id">Elemento o Libro Prestado</label>
                            <input title="No Pudes Editar Este Campo" wire:model="ArticuloPrestado"  disabled type="text" class="form-control" id="elementos_id" placeholder="Elemento o Libro Prestado">@error('elementos_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                       
                        <div class="form-group">
                            <label for="Usuario">Usuario</label>

                        
                       
                    <input type="text" title="No Pudes Editar Este Campo" wire:model="usuarioDeudor" disabled class="form-control" id="usuariodeudor">
                              </select>
                            
                        </div>

                      
                        <div class="form-group">
                            <label for="elementos_id">Cantidad Prestada</label>
                            <input title="No Pudes Editar Este Campo"    @disabled(true) disabled wire:model="CantidadPrestada" type="text" class="form-control" id="elementos_id" placeholder="Elementos Id">@error('elementos_id') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                           
                            <label for="EstadoPrestamo" class="form-label">Estado Del Prestamo</label>
                            <select class="form-select "  id="esatdoprestamo" wire:model="Estado_Prestamo">
                              
                                <option value="Activo">Activar</option>
                                <option value="Inactivo">Inactivar</option>
                                
                            </select>
                           
                        </div>
    
                   
@csrf

            </div>
            <div class="modal-footer mt-2">
                <button wire:click="cancelar" type="button" class="btn btn-danger text-white"
                    data-bs-dismiss="modal">Cancelar</button>

                   
                <button type="button" class="btn btn-warning text-white " wire:click="cambiarEstadoPrestamo()"  id="liveToastBtn">Actualizar</button>
            </div>
        </form>
        </div>
    </div>
</div>















<!-- Edit Modal -->
<div wire:ignore.self class="modal fade" id="VerDetallesPrestamo" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
       <div class="modal-content">
            
            <div class="modal-body">
                <div class="card mt-0">
                    <h5 class="card-header bg-primary text-white text-center" >Elemento Prestado :{{$detalleElemento}} </h5> 
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                
                                <th colspan="3"> Fecha Prestamo:{{$fechaDetalle}} </th>
                                
                              </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td colspan="2">Bibliotecario:  </td>
                                    <td>{{$bibliotecario}}</td>
                                   
                                  </tr>
                              <tr>
                                <td colspan="2">Cantidad Prestada:  </td>
                                <td>{{$cantidadPrestadaDetalle}}</td>
                               
                              </tr>
                              <tr>
                                <td colspan="2">Usuario Deudor: </td>
                                <td> {{$nombreDeudor}} {{$apellidoDeudor}}</td>
                                
                              </tr>
                              <tr>
                                <td colspan="2">Grado: </td>
                                <td>{{$gradoDeudor}}</td>
                              </tr>

                              <tr>
                                <td colspan="2">Identificaciòn :  </td>
                                <td>  {{$tipoDocDeudor}}: {{$numeroiDeudor}}  </td>
                              </tr>

                              
                              <tr>
                                <td colspan="2">Celular:  </td>
                                <td>   {{$celularDeudor}} </td>
                              </tr>

                              
                              <tr>
                                <td colspan="2">Direcciòn:  </td>
                                <td>  {{$direccionDeudor}}  </td>
                              </tr>
                              <tr>
                                <td colspan="2">Estado Prestamo:  </td>
                                <td>  {{$estadoDetalle}}  </td>
                              </tr>
                            </tbody>
                          </table>
                    </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                
            </div>
       </div>
    </div>
</div>
