<?php
$user_type = '';
if (Auth::check()) {

  $user = auth()->user();
  $iduser = $user->id;
  $user_type = $user->role;

  $lg = $user['lg'];
  $displayfr = '';
  $displayen = '';
  $displaypl = '';
  if ($lg == 'fr' || $lg == '') {
    $langue = 'Fr';
    $displayfr = 'display:none';
  }
  if ($lg == 'en') {
    $langue = 'En';
    $displayen = 'display:none';
  }
  if ($lg == 'pl') {
    $langue = 'Pl';
    $displaypl = 'display:none';
  }
}

?>
<style>
  .sidebar .nav-item .collapse .collapse-inner {
    font-size: 12px;
  }

  #accordionSidebar a:hover {
    color: black !important;
  }

  .nav-item i {
    color: #505050 !important;
    /*padding-left: 15px;*/
  }

  .nav-link span {
    color: black;
  }

  .hidemobile {
    display: contents;
  }

  .collapse-item i {
    margin-right: 10px;
  }

  .toggled hr {
    display: none !important;
  }

  .toggled .nav-link {
    margin-bottom: 25px !important;
  }

  @media (min-width: 481px) and (max-width: 767px) {
    /*.fas {
      margin-left: -15px !important;
    }*/
    .nav-item .nav-link{
      font-size:12px;
      margin-bottom:10px;
    }
    .nav-item .nav-link span{
      display: block;
    }
    .mb-2m{
      margin-bottom:20px!important;
    }
  }

  @media (min-width: 320px) and (max-width: 480px) {
    /*.fas {
      margin-left: -15px !important;
    }*/
      .nav-item .nav-link{
      font-size:12px;
      margin-bottom:10px;
    }
      .nav-item .nav-link span{
      display: block;
    }
      .mb-2m{
      margin-bottom:20px!important;
    }
  }
</style>
<!-- Sidebar -->
<ul class="navbar-nav bg-dark sidebar sidebar-dark  accordion" id="accordionSidebar" style="background:linear-gradient(to right, #fef4dc 0%, #ffe79d 90%) !important; font-size:13px">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" @if(auth()->user()->user_role==1 || auth()->user()->user_role==2 ) href="{{route('adminhome')}}" @else href="{{route('dashboard')}}" @endif >
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
      @if(auth()->user()->user_role==1 || auth()->user()->user_role==2 )
        <a class="nav-link" href="{{route('adminhome')}}">
      @else
        <a class="nav-link" href="{{route('dashboard')}}">
      @endif
      <i class="fas fa-tachometer-alt"></i> <span>Accueil</span>
      </a>
    </li>

  @if(auth()->user()->user_role < 5 || auth()->user()->user_role == 6 || auth()->user()->user_role == 7 || auth()->user()->user_role == 8 )
    <li class="nav-item mb-2m">
      <a class="nav-link" href="{{route('home')}}">
        <i class="fas fa-fw fa-chart-bar"></i> <span  >{{__('msg.My ')}} {{__('msg.Statistics')}}</span>
      </a>
    </li>
    @if(auth()->user()->user_role==1 || auth()->user()->user_role==2 )
    <li class="nav-item mb-2m">
      <a class="nav-link" href="{{route('recap')}}">
        <i class="fas fa-fw fa-chart-pie"></i> <span>Mon Récapitulatif</span>
      </a>
    </li>
    @endif
    @endif
    <li class="nav-item">
      <a class="nav-link" href="{{route('search')}}">
        <i class="fas fa-fw fa-search"></i> <span>{{__('msg.Customers')}}</span>
      </a>
    </li>
    @if(auth()->user()->user_role == 1 )
    <li class="nav-item">
      <a class="nav-link" href="{{route('communications.index')}}">
        <i class="fas fa-fw fa-envelope-open-text"></i> <span>Communications</span>
      </a>
    </li>
    @endif

    @if(auth()->user()->user_role==7)
    <li class="nav-item mb-2m">
      <a class="nav-link" href="{{route('mestaches')}}">
        <i class="fas fa-fw fa-tasks"></i> <span >{{__('msg.Activities of my customers')}}</span>
      </a>
    </li>
    @elseif($user->user_role==1 || $user->user_role== 2 || $user->user_role== 5 || $user->user_role== 6 || $user->user_role== 8 )
    <li class="nav-item mb-2m">
      <a class="nav-link" href="{{route('taches.index')}}">
        <i class="fas fa-fw fa-tasks"></i> <span >{{__('msg.Activities tracking')}}</span>
      </a>
    </li>
    @elseif($user->user_role!= 5)
    <li class="nav-item mb-2m">
      <a class="nav-link" href="{{route('mestaches')}}">
        <i class="fas fa-fw fa-tasks"></i> <span >{{__('msg.Activities tracking')}}</span>
      </a>
    </li>
    @endif
    <li class="nav-item">
      <a class="nav-link" href="{{route('offres.liste')}}">
        <i class="fas fa-fw fa-gift"></i> <span>{{__('msg.Offers')}}</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{route('retours.list')}}">
        <i class="fas fa-fw fa-comment-alt"></i> <span>{{__('msg.Complaints')}}</span>
      </a>
    </li>



    @if(auth()->user()->user_role != 5 )
    <li class="nav-item">
      <a class="nav-link" href="{{route('agenda')}}">
        <i class="fas fa-calendar-alt"></i> <span>{{__('msg.My')}} {{__('msg.Diary')}}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{route('rendezvous.create',['id'=>0])}}" >
        <i class="fas fa-calendar-day"></i> <span>{{__('msg.Appointments')}}</span>
      </a>
    </li>
    @endif

    @if(auth()->user()->user_role==1 || auth()->user()->user_role==2 )
    <li class="nav-item">
      <a class="nav-link" href="{{route('map.parcours')}}">
        <i class="fas fa-map-marker-alt"></i> <span>Parcours</span>
      </a>
    </li>
    @endif

    <li class="nav-item">
      <a class="nav-link" href="{{route('help')}}">
        <i class="fas fa-fw fa-book"></i> <span>{{__('msg.Help')}}</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{route('tickets.index')}}">
        <i class="fas fa-fw fa-comments"></i> <span>Support</span>
      </a>
    </li>

    <ul style="margin-top:20px;" id="" class="nav text-center  ">
      <br>
      <li class="dropdown menu-item">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <img <?php if ($lg == 'fr' || $lg == '') { ?> src="{{ URL::asset('img/fr.png')}}" <?php } ?> <?php if ($lg == 'en') { ?> src="{{ URL::asset('img/en.png')}}" <?php } ?> <?php if ($lg == 'pl') { ?> src="{{ URL::asset('img/pl.png')}}" <?php } ?> style="width:25px;margin-right:5px" title="<?php echo $langue; ?>">
          <span class="lang"><?php echo $langue; ?></span>
          <span class="caret"></span>
        </button>
        <ul class="dropdown-menu   dropdown-menu-right shadow animated--grow-in" style="padding-left:20px;padding-top:10px;">
          <li style="margin-bottom:12px;<?php echo $displayfr; ?>">
            <div id="lg-fr" style="cursor:pointer;" onclick="setlanguage('fr');">
              <img src="{{ URL::asset('img/fr.png')}}" style="width:20px;margin-bottom:5px;" title="Français">   Français
            </div>
          </li>
          <li style="margin-bottom:12px;<?php echo $displayen; ?>">
            <div id="lg-en" style="cursor:pointer" onclick="setlanguage('en');">
              <img src="{{ URL::asset('img/en.png')}}" style="width:20px" title="English">   English
            </div>
          </li><!--
    <li style="margin-bottom:12px;<?php // echo $displaypl;
                                  ?>">
      <div id="lg-pl" style="cursor:pointer" onclick="setlanguage('pl');">
        <img src="{{ URL::asset('img/pl.png')}}" style="width:20px;margin-bottom:5px;" title="polski">   Polski
      </div>
    </li>-->
        </ul>
      </li>
    </ul>

    <br><br>
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>