@php
$user = auth()->user();
$data=  DB::select ("  CALL `sp_affiche_cours`(); ");
@endphp
<style>
   .navbar-nav small {
     font-size: 10px;
     max-width: 150px;
     margin-left: 10px;
     margin-right: 10px;
   }
   .bg-dark{
    background-color:#f6c23e30!important;
    }
    .bg-dark{
    background-color:#f6c23e30!important;
    }
    .table th{
      font-size:12px!important;
    }
    .table td{
      font-size:11px!important;
    }
    .row{
      padding: 3px 3px !important;
    }
    .table-container {
      max-height: 300px; /* Limitez la hauteur de la zone d'affichage */
      overflow-y: auto; /* Activez le défilement vertical si le contenu dépasse la hauteur définie */
      width:100%;
    }
    .navbar small{
      color:black;
    }
    .tendances{
      list-style:none;
      line-height:8px;
      font-size:7px;
      padding-left:0px!important;
      padding-top:5px!important;
    }

@media (min-width: 1281px) {
 .hidepc{
  display:none;
  }
}
@media (min-width: 1025px) and (max-width: 1280px) {
  .hidepc{
  display:none;
  }
}

 </style>

 <!-- Topbar -->
 <nav class="navbar navbar-expand navbar-light bg-dark topbar mb-3 static-top shadow" style="height:60px!important">

   <!-- Sidebar Toggle (Topbar) -->
   <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
     <i style="color:#f2ba01" class="fa fa-bars"></i>
   </button>

   <!-- Topbar Navbar -->
   <div class="navbar-nav ml-5 mr-3 hidemobile">
    <div id="gold" class="pb-10 ml-5">{{__("msg.Gold")}}</div><br><small>{{$data[0]->cours_au}}</small><div id="silver" class="pb-10">{{ __("msg.Silver")}}</div><br><small>{{$data[0]->cours_ag}}</small><div id="platine" class="pb-10">Plat</div><br><small>{{$data[0]->cours_pt}}</small><div id="pallad" style="color:black" class="pb-10">Pall</div><br><small>{{$data[0]->cours_pd}}</small>
   </div>

   <div class="  hidepc hidetablette" >
    <ul class="tendances"   >
      <li> {{__("msg.Gold")}} - <small>{{$data[0]->cours_au}}</small> </li>
      <li> {{__("msg.Silver")}} - <small>{{$data[0]->cours_ag}}</small> </li>
      <li> Platine - <small>{{$data[0]->cours_pt}}</small> </li>
      <li> Palladium - <small>{{$data[0]->cours_pd}}</small> </li>
    </ul>
  </div>
   <div class="navbar-nav ml-auto">

     <!-- Nav Item - Messages -->

     <div class="topbar-divider d-none d-sm-block"></div>

     <!-- Nav Item - User Information -->
     <li class="nav-item dropdown no-arrow">
       <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
         <span class="mr-2 d-none d-lg-inline  small" style="color:black"> {{$user['name']}} {{$user['lastname'] }}</span>
         <img class="img-profile rounded-circle" src="{{ URL::asset('img/person.jpg')}}">
       </a>
       <!-- Dropdown - User Information -->
       <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
         <?php if ($user['user_type'] == 'admin' || $user['user_type'] == 'adv') { ?>
           <a class="dropdown-item" href="{{route('dashboard')}}">
             <i class="fas fa-bars fa-sm fa-fw mr-2 text-gray-400"></i>
               Tableau de bord
           </a>
         <?php } ?>
          <!--
         <a class="dropdown-item" href="{{route('profile')}}">
           <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
           {{__('msg.My Profile')}}
         </a>
         -->
         <div class="dropdown-divider"></div>
         <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
           <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
              {{__('msg.Logout')}}
         </a>
       </div>
     </li>

     </ul>

 </nav>
 <!-- End of Topbar -->