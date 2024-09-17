@php $user = auth()->user(); @endphp
<style>
  .navbar-nav small {
    font-size: 10px;
    max-width: 150px;
    margin-left: 10px;
    margin-right: 10px;
  }

  .menu-link {
    font-weight: bold;
    color: #000;
    margin-left: 50px
  }

  .bg-dark {
    background-color: #f6c23e30 !important;
  }
/*
  .table-container {
    position: relative;
    height: 400px;
    overflow:scroll;
  }

  .table-container thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    bac
  }
  */
</style>

<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-dark topbar static-top shadow">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i style="color:#f2ba01" class="fa fa-bars"></i>
  </button>

  <!-- Topbar Navbar -->
  <div class="navbar-nav ml-5 mr-3 hidemobile">
  </div>
  <a class="menu-link hidemobile" href="{{route('adminhome')}}">Accueil</a>
  <a class="menu-link hidemobile" href="{{route('search')}}">Clients</a>
  <a class="menu-link hidemobile" href="{{route('taches.index')}}">Activités</a>

  <div class="navbar-nav ml-auto">
    <!-- Nav Item - Messages -->
    <div class="topbar-divider d-none d-sm-block"></div>
    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-dark small"> {{$user['name']}} {{$user['lastname'] }}<br></span>
        <img class="img-profile rounded-circle" src="{{ URL::asset('img/profile.svg')}}">
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
<!--    <a class="dropdown-item" href="{{route('profile')}}">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          {{__('msg.My Profile')}}
        </a>
        <div class="dropdown-divider"></div>

-->

        <!-- <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a> -->
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          {{__('msg.Logout')}}
        </a>
      </div>
    </li>

    </ul>

</nav>
<!-- End of Topbar -->