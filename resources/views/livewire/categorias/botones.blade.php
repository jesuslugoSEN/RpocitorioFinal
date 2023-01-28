<a data-bs-toggle="modal" data-bs-target="#Actualizar"
                                                class=" bi bi-pencil-square text-white btn btn-info "
                                                wire:click="edit({{ $row->id }})"> </a>
                                            <a class="btn btn-danger bi bi-trash3-fill  text-white "
                                                onclick="confirm('Confirm Delete Libro id {{ $row->id }}? \nDeleted Libros cannot be recovered!')||event.stopImmediatePropagation()"
                                                wire:click="destroy({{ $row->id }})"> </a>
                                            <a data-bs-toggle="modal" data-bs-target="#verlibro"
                                                class=" bi bi bi-eye-fill text-white btn btn-warning "
                                                wire:click="edit({{ $row->id }})"> </a>