@extends('layouts.app')
@section('title', __('Gestion De Libros'))
@section('content')




    @include('partials.sidebar')



    <section class="home-section ">
        @include('partials.nav')



        @livewire('libros')

    </section>


    <div class="mt-5 ">
        @include('partials.footer')
    </div>

@endsection


<!--------Cerrar Modales Script Inicio---------->

<script>
    window.addEventListener('cerrar', event => {
        $('#añadirLibroModal').modal('hide')
        $('#actualizarLibroModal').modal('hide')
        if ($('.modal-backdrop').is(':visible')) {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        };




    });
</script>

<!--------Cerrar Modales Script   Fin---------->
