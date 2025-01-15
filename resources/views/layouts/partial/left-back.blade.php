<?php
$user_type = '';
if (Auth::check()) {

  $user = auth()->user();
  $iduser = $user->id;
  $user_type = $user->role;
}
?>
<style>
  #accordionSidebar a:hover {
    color: black !important;
  }

  .nav-item i {
    color: #505050 !important;
    /*padding-left: 15px;*/
  }
  .nav-link span{
    color:black;
  }

  .hidemobile {
    display: contents;
  }
  .collapse-item i{
    margin-right:10px;
  }
  .toggled hr{
    display:none!important;
  }
  .toggled .nav-link {
    margin-bottom:25px!important;
  }
  @media (min-width: 481px) and (max-width: 767px) {
    .fas {
      margin-left: -15px !important;
    }
  }

  @media (min-width: 320px) and (max-width: 480px) {
    .fas {
      margin-left: -15px !important;
    }
  }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark  accordion" id="accordionSidebar" style="background-color:#f3f3f3!important">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" @if(auth()->user()->role=='admin' || auth()->user()->role=='dirQUA' ) href="{{route('adminhome')}}" @else href="{{route('dashboard')}}" @endif >
    <!--<div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>-->
    <div class="sidebar-brand-text mx-3"><img width="100" src="{{ URL::asset('img/logo.png')}}" /></div>
  </a>

  <!-- Divider -->

  <!-- Nav Item - Dashboard -->

  <!-- Divider -->
  <hr class="sidebar-divider">




  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseVG" aria-expanded="true" aria-controls="collapseVG">
      <i class="fas fa-fw fa-chart-line"></i>
      <span>Vue générale</span>
    </a>
    <div id="collapseVG" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar"  >
      <div class="bg-dark py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('home')}}">
          <i class="fas fa-fw fa-chart-bar"></i> <div class="hidemobile"> {{__('msg.My ')}} </div>{{__('msg.Statistics')}}
        </a>
        <a class="collapse-item" href="{{route('recap')}}">
          <i class="fas fa-fw fa-chart-pie"></i> Mon Récapitulatif
        </a>
      </div>
    </div>
  </li>

  <hr class="sidebar-divider d-none d-md-block">


  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients" aria-expanded="true" aria-controls="collapseClients">
      <i class="fas fa-fw fa-users"></i>
      <span>Clients <label class="hidemobile">& Prospects</label></label></span>
    </a>
    <div id="collapseClients" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar">
      <div class="bg-dark py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('search')}}">
          <i class="fas fa-fw fa-search"></i> {{__('msg.Customers')}}
        </a>
        <a class="collapse-item" href="{{route('communications.index')}}">
          <i class="fas fa-fw fa-envelope-open-text"></i> Communications
        </a>
        @if($user->role=='commercial')
        <a class="collapse-item" href="{{route('mestaches')}}">
          <i class="fas fa-fw fa-tasks"></i> {{__('msg.Activities of my customers')}}
        </a>
        @elseif($user->role=='admin')
        <a class="collapse-item" href="{{route('taches.index')}}">
          <i class="fas fa-fw fa-tasks"></i> {{__('msg.Activities tracking')}}
        </a>
        @else
        <a class="collapse-item" href="{{route('mestaches')}}">
          <i class="fas fa-fw fa-tasks"></i> {{__('msg.Activities tracking')}}
        </a>
        @endif
      </div>
    </div>
  </li>

  <hr class="sidebar-divider d-none d-md-block">

  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGestion" aria-expanded="true" aria-controls="collapseGestion">
      <i class="fas fa-fw fa-calendar"></i>
      <span>Gestion interne</span>
    </a>
    <div id="collapseGestion" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
      <div class="bg-dark py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('retours.list')}}">
          <i class="fas fa-fw fa-comment-alt"></i>{{__('msg.Complaints')}}
        </a>
        <a class="collapse-item" href="{{route('agenda')}}">
          <div class="hidemobile"><i class="fas fa-calendar-alt"></i> {{__('msg.My')}} </div>{{__('msg.Diary')}}
        </a>
        <a class="collapse-item" href="{{route('rendezvous.create',['id'=>0])}}">
          <i class="fas fa-calendar-day"></i>{{__('msg.Appointments')}}<br>{{__('msg.excluding customers')}}
        </a>
      </div>
    </div>
  </li>
  <hr class="sidebar-divider d-none d-md-block">
  @if($user->role=='admin')
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdmin" aria-expanded="true" aria-controls="collapseAdmin">
      <i class="fas fa-fw fa-user-tie"></i>
      <span>Administration</span>
    </a>
    <div id="collapseAdmin" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar" >
      <div class="bg-dark py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('map.parcours')}}"><i class="fas fa-map-marker-alt"></i> Parcours </a>
      </div>
    </div>
  </li>
  <hr class="sidebar-divider d-none d-md-block">
  @endif
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAssist" aria-expanded="true" aria-controls="collapseAssist">
      <i class="fas fa-fw fa-life-ring"></i>
      <span>Assistance</span>
    </a>
    <div id="collapseAssist" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar"  >
      <div class="bg-dark py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{route('help')}}">
          <i class="fas fa-book"></i> {{__('msg.Help')}}
        </a>
        <a class="collapse-item" href="{{route('tickets.index')}}">
          <i class="fas fa-comments"></i> Support
        </a>
      </div>
    </div>
  </li>

  <br><br>
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>



  <!--
<li class="nav-item">
  <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
    <i class="fas fa-fw fa-folder"></i>
    <span>Pages</span>
  </a>
  <div id="collapsePages" class="collapse show" aria-labelledby="headingPages" data-parent="#accordionSidebar" style="">
    <div class="bg-white py-2 collapse-inner rounded">
      <h6 class="collapse-header">Login Screens:</h6>
      <a class="collapse-item" href="login.html">Login</a>
      <a class="collapse-item" href="register.html">Register</a>
      <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
      <div class="collapse-divider"></div>
      <h6 class="collapse-header">Other Pages:</h6>
      <a class="collapse-item" href="404.html">404 Page</a>
      <a class="collapse-item" href="blank.html">Blank Page</a>
    </div>
  </div>
</li>--->