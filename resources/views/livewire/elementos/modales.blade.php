<!-- Crear Elemento Modal -->
<div wire:ignore.self class="modal fade" id="crearNuevoElementoModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center">
                <h5 class="modal-title text-center text-white" id="exampleModalLabel">Añadir Elemento</h5>
                <button type="button" wire:click="cancelar" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form>

                    @csrf
                    <div class="form-group mb-1">
                        <label for="nombre">Nombre Elemento</label>
                        <input wire:model="nombre" type="text"
                            class="form-control @error('nombre') is-invalid @enderror " id="nombre"
                            placeholder="Nombre" required>


                        @error('nombre')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group mb-1">
                        <label for="cantidad">Cantidad Del Elemento</label>
                        <input wire:model="cantidad" type="number " pattern="" min="0"
                            class="form-control @error('cantidad') is-invalid @enderror " id="cantidad "required
                            placeholder="Cantidad">
                        @error('cantidad')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group mb-1">
                        <label for="categoria_id">Categoría</label>


                        <select wire:model="categoria_id" name="categoria_id"
                            class="form-select @error('categoria_id') is-invalid @enderror" required>

                            <option>Elije Una Categoría</option>
                            @foreach ($categorias as $row)
                                <option class="mt-2" value="{{ $row->id }} ">{{ $row->nombre }}</option>
                            @endforeach

                        </select>
                        @error('categoria_id')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror

                    </div>

                    <div class="form-group ">
                        <label for="descripcion">Descripción</label>

                        <textarea required class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"
                            wire:model="descripcion" rows="3"></textarea>
                        @error('descripcion')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror

                    </div>

                    <div class="form-group mb-1">

                        <label for="Estado" class="form-label">Estado</label>
                        <select required class="form-select @error('Estado') is-invalid @enderror" name="Estado"
                            id="Estado" wire:model="Estado">
                            <option selected>Selecione el estado incial del elemento</option>
                            <option value="Disponible">Disponible</option>
                            <option value="Inactivo">Inactivo</option>

                        </select>
                        @error('Estado')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

            </div>




            </form>
            <div class="modal-footer d-flex justify-content-between">
                <button wire:click="cancelar" type="button" class="btn btn-danger text-white col-3"
                    data-bs-dismiss="modal">Cancelar</button>

                <button type="button" wire:click.prevent="crearElemento()"
                    class="btn btn-warning col-3 text-white">Guardar</button>
            </div>
            </form>
        </div>
    </div>
</div>



















<!-- Actualizar Elemento Modal -->
<div wire:ignore.self class="modal fade" id="actualizarNuevoElementoModal" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-center">
                <h5 class="modal-title text-center text-white" id="exampleModalLabel">Añadir Elemento</h5>
                <button type="button" wire:click="cancelar" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <form>

                    @csrf
                    <div class="form-group mb-1">
                        <label for="nombre">Nombre Elemento</label>
                        <input wire:model="nombre" type="text"
                            class="form-control @error('nombre') is-invalid @enderror " id="nombre"
                            placeholder="Nombre" required>


                        @error('nombre')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group mb-1">
                        <label for="cantidad">Cantidad Del Elemento</label>
                        <input wire:model="cantidad" type="number " pattern="" min="0"
                            class="form-control @error('cantidad') is-invalid @enderror " id="cantidad "required
                            placeholder="Cantidad">
                        @error('cantidad')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group mb-1">
                        <label for="categoria_id">Categoría</label>


                        <select wire:model="categoria_id" name="categoria_id"
                            class="form-select @error('categoria_id') is-invalid @enderror" required>


                            @foreach ($categorias as $row)
                                <option class="mt-2" value="{{ $row->id }} ">{{ $row->nombre }}</option>
                            @endforeach

                        </select>
                        @error('categoria_id')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror

                    </div>

                    <div class="form-group ">
                        <label for="descripcion">Descripción</label>

                        <textarea required class="form-control @error('descripcion') is-invalid @enderror" name="descripcion" id="descripcion"
                            wire:model="descripcion" rows="3"></textarea>
                        @error('descripcion')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror

                    </div>

                    <div class="form-group mb-1">

                        <label for="Estado" class="form-label">Estado</label>
                        <select required class="form-select @error('Estado') is-invalid @enderror" name="Estado"
                            id="Estado" wire:model="Estado">
                            <option selected>Selecione el estado incial del elemento</option>
                            <option value="Disponible">Disponible</option>
                            <option value="Inactivo">Inactivo</option>

                        </select>
                        @error('Estado')
                            <span class="invalid-feedback" role="alert"> <strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

            </div>




            </form>
            <div class="modal-footer ">
                <button wire:click="cancelar" type="button" class="btn btn-danger text-white col-3"
                    data-bs-dismiss="modal">Cancelar</button>

                    <button type="button" wire:click.prevent="actualizarElemento()"
                    class="btn btn-warning col-3 text-white">Actualizar</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Ver Detalles  Modal -->
<div wire:ignore.self class="modal fade" id="verDetallesElemento" data-bs-backdrop="static" tabindex="-1" role="dialog"
     aria-labelledby="createDataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDataModalLabel">Ver Detalles Del Elemento</h5>
                <button wire:click.prevent="limpiarCamposInput()" type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-12 ">
                            <h5 class="card-header bi bi-bookmark-star-fill bg-primary text-white">Nombre del elemento:{{ $nombre }}</h5>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">

                                <p class="card-text bi bi-caret-right-fill">Descripción:{{ $descripcion }}</p>
                                <p class="card-text bi bi-caret-right-fill">Cantidad:{{ $cantidad }}</p>
                                <p class="card-text bi bi-caret-right-fill text-warning">Estado :<small
                                        class="  text-warning   " @disabled(true)> <strong>{{ $Estado }}</strong>  </small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click="limpiarCamposInput()" class="btn btn-warning text-white close-btn"
                        data-bs-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>




























