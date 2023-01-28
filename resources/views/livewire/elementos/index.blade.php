@extends('layouts.app')
@section('title', __('Gestion De Elementos'))
@section('content')




    @include('partials.sidebar')

    

    <section class="home-section " >
        @include('partials.nav')


        
        

       
           
                
                    

                        
                        @livewire('elementos')
                       
                       
                
                
           
         

           


    @include('partials.footer')
    

                     

    </section>
   







@endsection



<!--------Cerrar Modales Script Inicio---------->

<script>
    window.addEventListener('cerrar', event => {
        $('#crearNuevoElementoModal').modal('hide')
        $('#actualizarNuevoElementoModal').modal('hide')
        if ($('.modal-backdrop').is(':visible')) {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        };




    });
</script>

<!--------Cerrar Modales Script   Fin---------->