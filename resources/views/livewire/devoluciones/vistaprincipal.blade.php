

<div>


    <div class="container-fluid">

        <div class="justify-content-center">




            <div class="row m-1   d-flex " style="height: 70%" >

                <div class="  col-10"    >

                        <!--------LINICIO LINKS TABS -------->
                        <ul class="nav nav-tabs mt-2" id="myTab" role="tablist">


                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">Gestión Devoluciones</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">Restaurar Devoluciones</button>
                            </li>
                            <li class="nav-item " role="presentation">

                            </li>











                        </ul>



                        <div class="tab-content h-100 " id="pills-tabContent">
                            <!----------- INICIO CONTENEDOR 1  ------------->

                            <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
								@include('livewire.devoluciones.TablaPrincipalDevoluciones')
                            </div>
                            <!----------- FIN CONTENEDOR 1  ------------->

                            <!----------- INICIO CONTENEDOR 2  ------------->
                            <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab" tabindex="0">
								@livewire('devoluciones.tabla-devoluciones-inactivas')
                            </div>
                            <!----------- FIN CONTENEDOR 2  ------------->


                            <!----------- INICIO CONTENEDOR 3  ------------->




                            <!----------- FIN  CONTENEDOR 3  ------------->






















                    </div>
					@include('partials.footer')
                </div>


                <div class="col-2 mt-4  p-2 h-100  ">
                    <!------Formulario de Devolucion------>






                 </div>


				 @include('livewire.devoluciones.modales')
            </div>

        </div>

    </div>

</div>




