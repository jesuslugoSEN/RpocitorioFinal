
@if (session()->has('correcto'))
<div wire:poll.7s class="alert  card alert-success alert-dismissible fade show " role="alert">
  
    <strong>
         Correcto...! 
    </strong><p class="  bi bi-check-circle-fill">{{ session('correcto') }}</p>
</div>

<script>
    var alertList = document.querySelectorAll('.alert');
    alertList.forEach(function(alert) {
        new bootstrap.Alert(alert)
    })
</script>
@endif

@if (session()->has('error'))
<div wire:poll.7s class="alert  card alert-danger alert-dismissible fade show " role="alert">
  
    <strong>
         Error...! 
    </strong><p class="  bi bi-check-circle-fill">{{ session('error') }}</p>
</div>

<script>
    var alertList = document.querySelectorAll('.alert');
    alertList.forEach(function(alert) {
        new bootstrap.Alert(alert)
    })
</script>
@endif