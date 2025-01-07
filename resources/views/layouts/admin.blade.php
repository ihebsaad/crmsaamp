


<!DOCTYPE html>
<html  >
@include('layouts.partial.head-back')



<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">


    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

@include('layouts.partial.admin-back')

        <!-- Begin Page Content -->
        <div class="container-fluid" style="min-height:600px">

          <!-- Page Heading
          <h1 class="h3 mb-4 text-gray-800"> </h1>
          -->
 @if ($errors->any())
             <div class="alert alert-danger">
                 <ul>
                     @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                     @endforeach
                 </ul>
             </div><br />
         @endif

    @if (!empty( Session::get('success') ))
        <div class="alert alert-success">

        {{ Session::get('success') }}
        </div>
    @endif


     @yield('content')




        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer-->
      <footer class="sticky-footer bg-dark">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>  </span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('msg.Logout')}}?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body text-center"  style="height:80px!important"><b style="font-size:18px;color:black">{{__('msg.Do you really want to log out?') }}</b></div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Cancel')}}</button>
          <a class="btn btn-primary" href="{{ route('logout') }}"    onclick="event.preventDefault();document.getElementById('logout-form').submit();">
            {{ __('msg.Logout') }}
          </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
            </form>
        </div>
      </div>
    </div>
  </div>

@php
  $user_id=auth()->user()->id;
  try{
  DB::select("SET @p0='$user_id' ;");
  $data=  DB::select ("  CALL `sp_affiche_cours`(@p0); ");
  }catch(\Exception $e){
    $data=null;
  }
@endphp
    <!-- Metals Modal-->
  <div class="modal fade" id="metalsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tendances</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        @if($data!='')
        <div class="modal-body text-center"  >
          <div id="gold" style="width:100%!important" class="pb-10">{{__("msg.Gold")}}</div><br><small class="mb-30">{{$data[0]->au}}<br>{{$data[1]->au}}</small>
          <div id="silver"  style="width:100%!important" class="pb-10 mt-30">{{ __("msg.Silver")}}</div><br><small class="mb-30">{{$data[0]->ag}}<br>{{$data[1]->ag}}</small>
          <div id="platine" style="width:100%!important" class="pb-10 mt-30">Plat</div><br><small class="mb-30">{{$data[0]->pt}}<br>{{$data[1]->pt}}</small>
          <div id="pallad" style="width:100%!important;color:black" class="pb-10 mt-30">Pall</div><br><small class="mb-30">{{$data[0]->pd}}<br>{{$data[1]->pd}}</small>
        </div>
        @endif
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">{{__('msg.Close')}}</button>
        </div>
      </div>
    </div>
  </div>

 @include('layouts.partial.footer-scripts-back')


</body>

</html>
