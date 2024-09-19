<?php
$user_type = '';
if (Auth::check()) {

  $user = auth()->user();
  $iduser = $user->id;
  $user_type = $user->user_type;
}
?>
<style>
  #accordionSidebar a:hover {
    color: black !important;
  }
  .nav-item i{
    color:#505050!important;
    padding-left:15px;
  }
  .hidemobile{
    display:contents;
  }

	@media (min-width: 481px) and (max-width: 767px) {
    .fas{
      margin-left:-15px!important;
    }
  }
	@media (min-width: 320px) and (max-width: 480px) {
    .fas{
      margin-left:-15px!important;
    }
  }

</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark  accordion" id="accordionSidebar" style="background-color:#f3f3f3!important">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('dashboard')}}">
    <!--<div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-laugh-wink"></i>
        </div>-->
    <div class="sidebar-brand-text mx-3"><img width="100" src="{{ URL::asset('img/logo.png')}}" /></div>
  </a>

  <!-- Divider -->

  <!-- Nav Item - Dashboard -->

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    <!--{{__('msg.Artisans, Manufacturers, Industrialists')}}-->
  </div>
  <hr class="sidebar-divider">

  <li class="nav-item   ">
  @if($user_type=='admin')
   <a class="nav-link" href="{{route('adminhome')}}">
      <i class="fas fa-tachometer-alt"></i>
      <span><div class="hidemobile">Mon </div>Tableau<div class="hidemobile"> de bord</div></span>
    </a>
  @else
  <a class="nav-link" href="{{route('dashboard')}}">
      <i class="fas fa-tachometer-alt"></i>
      <span><div class="hidemobile"> Mon </div>Tableau<div class="hidemobile"> de bord</div></span>
    </a>
  @endif


    <a class="nav-link" href="{{route('home')}}">
      <i class="fas fa-home" ></i>
      <span><div class="hidemobile"> Mes </div>Statistiques</span>
    </a>


    <a class="nav-link" href="{{route('search')}}">
      <i class="fas fa-users" ></i>
      <span> Clients </span>
    </a>
      <!--<hr class="sidebar-divider">-->

    <a class="nav-link" href="{{route('taches.index')}}" >
      <i class="fas fa-tasks" ></i>
      <span> Activités<div class="hidemobile"> de l'Agence</div></span>
    </a>
    <!--<hr class="sidebar-divider">
    <a class="nav-link"  href="{{route('offres.index')}}">
      <i class="fas fa-file-invoice-dollar" ></i>
      <span> Offres commerciales </span>
    </a>-->
    <!--<hr class="sidebar-divider">-->
    @if($user->user_type=='admin' || $user->email=='directeur.qualite@saamp.com' )
      <a class="nav-link" href="{{route('retours.index')}}" >
      <i class="fas fa-comments" ></i>
        <span> Réclamations </span>
      </a>
    @endif
    <a class="nav-link" href="{{route('agenda')}}" >
    <i class="fas fa-calendar" ></i>
      <span><div class="hidemobile"> Mon </div>Agenda</span>
    </a>
    <a class="nav-link" href="{{route('help')}}" >
    <i class="fas fa-book" ></i>
      <span> Guide <div class="hidemobile">d'utilisation</div></span>
    </a>
  <!--
    <hr class="sidebar-divider">
    <a class="nav-link" href="{{route('phone')}}">
    <i class="fas fa-phone-alt" ></i>
      <span> Télephonie </span>
    </a>
-->
  </li>



  <!-- Heading -->

  <div class="sidebar-heading">
    <!--   {{__('msg.Scrap Gold Buyers, Investors')}}-->
  </div>



  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->