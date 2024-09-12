<!DOCTYPE html>
<html>
<style>
  @media print {
    .no-print {
      display: none !important;
    }

    a{
      text-decoration:none!important;
      color:black!important;
    }
  }
</style>
@include('layouts.partial.head-back')

<div class="container-fluid">
  <a class="no-print" href="{{route('dashboard')}}"><img width="80" src="{{ URL::asset('img/logo.png')}}" class="ml-2 mt-2 mb-3" /></a>
  @yield('content')

  <div class="row pt-1 no-print">
            <div class="col-md-12">
                <button type="button" onclick="window.print()" class="btn-secondary btn  mt-5 no-print"><i class="fa fa-print"></i> Imprimer</button>
            </div>
        </div>
</div>
@include('layouts.partial.footer-scripts-back')

</body>
<script>
  // Lancer l'impression automatiquement à l'ouverture de la page
  window.addEventListener('load', function() {
    window.print();
  });
</script>

</html>