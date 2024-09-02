

<!--<script src="{{--  URL::asset('js/jquery-ui/jquery.ui.min.js') --}}" type="text/javascript"></script>-->
{{ csrf_field() }}


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.2/umd/popper.min.js" type="text/javascript"></script>


<script src="{{ URL::asset('bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>


    <!-- Bootstrap core JavaScript -->
    <script src="{{ URL::asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Core plugin JavaScript -->
    <script src="{{ URL::asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>

	<script  src="{{ asset('js/summernote.min.js') }}"  type="text/javascript"></script>
<script src="{{  URL::asset('js/custom_js/compose.js') }}" type="text/javascript"></script>

<!----- Datepicker ------->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <!--<script src="//bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/js/bootstrap-notify.min.js"></script>

  <!-- Admin -->
   <!-- Bootstrap core JavaScript
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-
  <script  src="{{  URL::asset('sbadmin/jquery-easing/jquery.easing.min.js') }}"  ></script>
-->
  <!-- Custom scripts for all pages-->
  <script   src="{{  URL::asset('sbadmin/js/sb-admin-2.min.js') }}" src="js/sb-admin-2.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mouse0270-bootstrap-notify/3.1.7/bootstrap-notify.js"></script>
<script>


    // Function to check window width and toggle class
    function checkWindowWidth() {
      if ($(window).width() < 768) {
        $(".sidebar").addClass("toggled");
      } else {
        $(".sidebar").removeClass("toggled");
      }
    }

    // Check window width on load
    $(document).ready(function() {
      checkWindowWidth();
    });
</script>

 <?php if  ($view_name == 'livraison')   { ?>

<?php } ?>


 @yield('footer_scripts')

