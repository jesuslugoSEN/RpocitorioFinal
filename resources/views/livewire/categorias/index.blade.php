@extends('layouts.app')
@section('title', __('Gestion De Categorias'))
@section('content')




    @include('partials.sidebar')



    <section class="home-section ">
        @include('partials.nav')









        @livewire('categorias')









    
           
        



    </section>








@endsection
















@vite(['resources/js/app.js', 'resources/js/jquery3.6.3.js'])

<script src="{{ asset('jquery3.6.3.js') }}"></script>

<script>
    window.addEventListener('cerrar', event => {
        $('#crearNuevaCategoriaModal').modal('hide')
        $('#actualizarCategoriaModal').modal('hide')
        if ($('.modal-backdrop').is(':visible')) {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        };




    });
</script>

<script>
    window.addEventListener('swal', function(e) {
        Swal.fire({
            title: e.detail.title,
            icon: e.detail.icon,
            iconColor: e.detail.iconColor,
            timer: 4000,
            toast: true,
            position: 'top-right',
            toast: true,
            showConfirmButton: false,
            
        });

       
    });
</script>





