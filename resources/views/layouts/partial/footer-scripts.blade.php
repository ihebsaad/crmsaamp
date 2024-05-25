
{{ csrf_field() }}

   <!-- Bootstrap core JavaScript -->
  <script    src="{{  URL::asset('site/vendor/jquery/jquery.min.js') }}"   ></script>
  <script    src="{{  URL::asset('site/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"     ></script>


<script  src="{{  URL::asset('site/js/jquery.slicknav.min.js') }}"  ></script>
<script type="text/javascript">
  $('ul#navigation').slicknav({
    prependTo:"#menumob"
  });
  
  $(document).ready(function(){

    $("select#elevestat").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".box").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".box").hide();
            }
        });
    }).change();
    $("select#mpreinscrit").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            if(optionValue){
                $(".partie").not("." + optionValue).hide();
                $("." + optionValue).show();
            } else{
                $(".partie").hide();
            }
        });
    }).change();

    $(".toggle-accordion").on("click", function() {
    var accordionId = $(this).attr("accordion-id"),
      numPanelOpen = $(accordionId + ' .collapse.in').length;
    
    $(this).toggleClass("active");

    if (numPanelOpen == 0) {
      openAllPanels(accordionId);
    } else {
      closeAllPanels(accordionId);
    }
  });

    $( "#reemail" ).focusout(function() {
    var valemail = $("#email").val();
    var valconfemail = $("#reemail").val();
    if (valemail != valconfemail)
    {$( "#alertemail" ).show();}
    else
        {$( "#alertemail" ).hide();}
  });  
  
    
});
</script>
 @yield('footer_scripts')