<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Fortuna Corporation') }}</title>

  <link rel="icon" href="{{ asset('img/logo/logo.png') }}" type="image/icon type">
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    function sideMenuCollapseOnMobile() {
      if (window.innerWidth <= 767 && $('body').hasClass('sidebar-open')) {
        $('[data-widget="pushmenu"]').PushMenu('collapse');
      }
    }
  </script>
  <link rel="manifest" href="{{ asset('manifest.json') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="background: #007bff;">
  <div class="wrapper" id="app">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
      <!-- <span> Fortuna Corporation ...</span> -->
      <img class="animation__wobble" src="{{ asset('img/logo/logo.png') }}" alt="Fortuna-Logo" height="160" width="160">
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu"><i class="fas fa-bars"></i></a>
        </li>
        <!-- <i class="nav-icon fas fa-file"></i> -->
        <!-- <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link" target="_blank">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link" target="_blank">Test</a>
        </li> -->
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <!-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> -->

       <!-- Notice -->
       <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="fa-solid fa-bell"></i>
            <span class="badge badge-warning navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li> -->
       <!-- Notice end -->

        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">15 Notifications</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-envelope mr-2"></i> 4 new messages
              <span class="float-right text-muted text-sm">3 mins</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
        </li> -->
        <!-- Add the button for displaying local notification at the top -->
        <!-- <button onclick="displayLocalNotification()">Display Local Notification</button> -->
        <li class="nav-item">
          <button class="nav-link text-gray text-sm" id="install-button">
            <i class="fa-solid fa-download"></i>
          </button>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <!-- <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
        <!-- <button id="send-notification-btn">Send Push Notification</button> -->
        <li class="nav-item dropdown">

          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            <span style="font-size: 10px; position:relative; top:1px; left: 2px; opacity: 1; display: inherite">
              @if(Auth::user()->online_status)
              <span>🟢</span>
              @else
              <span>🔴</span>
              @endif
            </span>
            <i class="fa fa-user-circle" aria-hidden="true" style="font-size: 18px; position:relative; top:2px;"></i>
            {{ Auth::user()->first_name }}
          </a>

          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            <router-link class="dropdown-item" class-active="active" :to="{ name: 'profile' }">
              <p><i class="nav-icon fas fa-user"></i> Profile </p>
            </router-link>
            <hr class="hr mb-4" />
            <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-sign-out"></i> {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar bg-info text-light elevation-4">
      <!-- Brand Logo -->
      <router-link class="brand-link" :to="{ name: 'dashboard'}">
        <img src="{{ asset('img/logo/logo.png') }}" alt="Logo" class="brand-image m-0" /><span class="brand-text font-weight-light">{{ config('app.name', 'Fortuna Corporation') }}</span>
        <!-- <span class="brand-text font-weight-light">AC-COUNTER</span> -->
      </router-link>
      <!-- <a href="index3.html" class="brand-link"> -->
      <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <!-- </a> -->

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
        </div>
      </div> -->

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item menu-open">
              <ul class="nav nav-treeview">
                <li class="nav-item">

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'dashboard'}">
                    <img src="{{ asset('img/icons/dashboard.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100">Dashboard</p>
                  </router-link>
                </li>

                <!-- <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'dashboard'}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p> Dashboard </p>
                  </router-link>
                </li> -->

                @if($authUserRoles[0]->roles[0]->name == 'agent')

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100">
                       Transactions list
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </router-link>
                  <ul class="nav nav-treeview ml-4">
                  <li class="nav-item">
                    <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transaction.index'}">
                    <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100"> In process transactions </p>
                    </router-link>
                  </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transactionscompleted.index'}">
                      <img src="{{ asset('img/icons/completed_transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Completed transactions </p>
                      </router-link>
                    </li>
                  </ul>
                </li>
                <!-- <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transaction.index'}">
                  <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Transactions </p>
                  </router-link>
                </li> -->

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentsdeposits.index'}">
                  <img src="{{ asset('img/icons/agent_deposit.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agents Deposits </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'sendmoney.index'}">
                  <img src="{{ asset('img/icons/send_money.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Withdrawals </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'profittorecharge.index' }" >
                  <img src="{{ asset('img/icons/commision_to_recharge.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Commission to recharge </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/loan.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100">
                       Loan management
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </router-link>
                  <ul class="nav nav-treeview ml-4">
                  <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentloan.index'}">
                      <img src="{{ asset('img/icons/loan.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Loan </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentloanrepay.index'}">
                      <img src="{{ asset('img/icons/loan_payment.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Loan payment </p>
                      </router-link>
                    </li>
                  </ul>
                </li>
                @elseif($authUserRoles[0]->roles[0]->name == 'envoy')

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentsdeposits.index'}">
                  <img src="{{ asset('img/icons/agent_deposit.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agents Deposits </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoydeposits.index'}">
                  <img src="{{ asset('img/icons/envoy_deposit.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> My Deposits </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'sendmoney.index'}">
                    <img src="{{ asset('img/icons/send_money.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agent withdrawals </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'cashout.index'}">
                  <img src="{{ asset('img/icons/cash_out.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Cashout </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoytransferts.index'}">
                  <img src="{{ asset('img/icons/envoy_transfer.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Envoy transfert </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentloanrepay.index'}">
                  <img src="{{ asset('img/icons/loan_payment.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                  <p class="text-yellow-100"> Loan payment </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoyagentinfos.index' }" >
                  <img src="{{ asset('img/icons/agent_info.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agent Infos </p>
                  </router-link>
                </li>

                @elseif($authUserRoles[0]->roles[0]->name == 'operator')

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100">
                       Transactions list
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </router-link>
                  <ul class="nav nav-treeview ml-4">
                  <li class="nav-item">
                    <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transaction.index'}">
                    <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100"> In process transactions </p>
                    </router-link>
                  </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transactionscompleted.index'}">
                      <img src="{{ asset('img/icons/completed_transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Completed transactions </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/account.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100">
                      Accounts management
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </router-link>
                  <ul class="nav nav-treeview ml-4">
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'accounts.index'}">
                      <img src="{{ asset('img/icons/account.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Account </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'accounttransferts.index'}">
                      <img src="{{ asset('img/icons/transafer_history.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Transferts history </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'cashout.index'}">
                  <img src="{{ asset('img/icons/cash_out.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Cashout </p>
                  </router-link>
                </li>

                @elseif($authUserRoles[0]->roles[0]->name == 'super-admin' || $authUserRoles[0]->roles[0]->name == 'admin' || $authUserRoles[0]->roles[0]->name == 'system-engineer')

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/users.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100">
                      Employees management
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </router-link>
                  <ul class="nav nav-treeview ml-4">
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'users.index'}">
                      <img src="{{ asset('img/icons/users.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p> Employees / Users</p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'departments.index'}">
                      <img src="{{ asset('img/icons/department.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p> Departements </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'roleandpermission.index'}">
                      <img src="{{ asset('img/icons/role.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p> Roles & permissions </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100">
                       Transactions list
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </router-link>
                  <ul class="nav nav-treeview ml-4">
                  <li class="nav-item">
                    <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transaction.index'}">
                    <img src="{{ asset('img/icons/transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100"> In process transactions </p>
                    </router-link>
                  </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'transactionscompleted.index'}">
                      <img src="{{ asset('img/icons/completed_transactions.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Completed transactions </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'sendmoney.index'}">
                  <img src="{{ asset('img/icons/send_money.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agent withdrawals </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentsdeposits.index'}">
                  <img src="{{ asset('img/icons/agent_deposit.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agents Deposits </p>
                  </router-link>
                </li>
                
                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoydeposits.index'}">
                  <img src="{{ asset('img/icons/envoy_deposit.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Envoy Deposits </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/account.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100">
                      Accounts management
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </router-link>
                  <ul class="nav nav-treeview ml-4">
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'accounts.index'}">
                      <img src="{{ asset('img/icons/account.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Account </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'accounttransferts.index'}">
                      <img src="{{ asset('img/icons/transafer_history.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Transferts history </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                  <img src="{{ asset('img/icons/provider.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100">
                      Providers management
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </router-link>
                  <ul class="nav nav-treeview ml-4">
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'providers.index'}">
                      <img src="{{ asset('img/icons/provider.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Providers </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'providerpayments.create'}">
                      <img src="{{ asset('img/icons/provider_payment.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> New payment </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'providerpayments.index'}">
                      <img src="{{ asset('img/icons/payment_history.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100"> Payments history </p>
                      </router-link>
                    </li>

                  </ul>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'cashins.index'}">
                  <img src="{{ asset('img/icons/cash_in.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Cash in </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'cashout.index'}">
                  <img src="{{ asset('img/icons/cash_out.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Cashout </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoytransferts.index'}">
                  <img src="{{ asset('img/icons/envoy_transfer.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Envoy transfert </p>
                  </router-link>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'profittorecharge.index' }" >
                  <img src="{{ asset('img/icons/commision_to_recharge.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Commission to recharge </p>
                  </router-link>
                </li>

                <li class="nav-item">
                <router-link href="#" class="nav-link text-light d-flex align-items-center" class-active="active" to="#">
                <img src="{{ asset('img/icons/loan.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                      <p class="text-yellow-100">
                       AG loan management
                        <i class="right fas fa-angle-left"></i>
                      </p>
                    </router-link>
                  <ul class="nav nav-treeview ml-4">
                  <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentloan.index'}">
                      <img src="{{ asset('img/icons/loan.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p> Agent loan </p>
                      </router-link>
                    </li>
                    <li class="nav-item">
                      <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'agentloanrepay.index'}">
                      <img src="{{ asset('img/icons/loan_payment.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p> AG loan payment </p>
                      </router-link>
                    </li>
                  </ul>
                </li>

                <li class="nav-item">
                  <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'envoyagentinfos.index' }" >
                  <img src="{{ asset('img/icons/agent_info.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                    <p class="text-yellow-100"> Agent Infos </p>
                  </router-link>
                </li>
  
                
                @else
                <li class="nav-header"></li>
                @endif

                <li class="nav-item">
                    <router-link class="nav-link text-light d-flex align-items-center" onclick="sideMenuCollapseOnMobile()" class-active="active" :to="{ name: 'userativities.show', params: { id: {{ Auth::user()->id }} } }">
                        <img src="{{ asset('img/icons/activity.png') }}" class="nav-icon mr-2 img-responsive rounded-md bg-light border border-yellow-100 opacity-75" alt="icon" style="max-width: 24px; height: auto;">
                        <p class="text-yellow-100">Activities</p>
                    </router-link>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer p-2" style="display: flex; justify-content: space-between;">
    <small style="display: flex; justify-content: space-between;"> <span class="mr-1 text-dark-gray fw-bold"> Developed by </span>
        <a href="https://www.redmotion-company.com" target="_blank" class="text-red-400"> <img src="{{ asset('img/logo/red-motion_logo.png') }}" style="display: inline; position:relative; bottom: 1px;" class="w-4 mr-0 pr-0" alt="Red-motion logo"/><span class="fw-bold" style="font-size: 12px;">Red Motion</span></a> 
          <span class="ml-1 mr-1 mt-0 mb-0 text-dark-gray fw-bold" style="font-size: 13px;">&</span>
        <a href="https://www.instagram.com/TephGab" target="_blank" class="text-blue-500"> <img src="{{ asset('img/logo/Teph-Gab_logo.png') }}" style="display: inline; position:relative; bottom: 1.5px;" class="w-4 mr-0 pr-0" alt="Teph-Gab logo"/><span class="fw-bold" style="font-size: 12px;">Teph Gab</span></a>
        </small>
      <div class="float-right d-none d-sm-inline-block">
        <small><b>Version</b> 10.0.3</small>
      </div>
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" integrity="sha512-fD9DI5bZwQxOi7MhYWnnNPlvXdp/2Pj3XSTRrFs5FQa4mizyGLnJcN6tuvUS6LbmgN1ut+XGSABKvjN0H6Aoow==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('js/app.js') }}"></script>
  <!-- jQuery -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <!-- Bootstrap -->
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

  <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>

  <!-- Include the Pusher JavaScript SDK -->
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  <script>
    //  Echo.private('useronline')
    //   .listen('UserOnlineStatusUpdated', (e) => {
    //   });
    // Echo.private('createtransaction')
    //   .listen('CreateTransactionEvent', (e) => {
    //     updateUnreadNotificationsCount();
    // console.log('Data updated:', e);
    // document.getElementById('operator-data').inneText = '@json(auth()->user()->unreadNotifications->count())';
    // });
  </script>
  <script src="{{ asset('/sw.js') }}"></script>
  <script>
    if (!navigator.serviceWorker.controller) {
      navigator.serviceWorker.register("/sw.js").then(function(reg) {
        console.log("Service worker has been registered for scope: " + reg.scope);
      });
    }
  </script>


  <!-- JavaScript to handle manual installation -->
  <script>
    // Check if the beforeinstallprompt event is supported
    if ('onbeforeinstallprompt' in window) {
      let deferredPrompt;

      // Listen for the beforeinstallprompt event
      window.addEventListener('beforeinstallprompt', (event) => {
        event.preventDefault(); // Prevent the automatic browser prompt
        deferredPrompt = event;

        // Show the install button
        const installButton = document.getElementById('install-button');
        installButton.style.display = 'block';

        // Handle the install button click
        installButton.addEventListener('click', () => {
          // Show the browser's installation prompt
          deferredPrompt.prompt();

          // Wait for the user's choice
          deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
              console.log('App installed by user');
            } else {
              console.log('App installation declined by user');
            }
            deferredPrompt = null;
            // Hide the install button
            installButton.style.display = 'none';
          });
        });
      });

      // Check if the PWA is installed when the page loads
      window.addEventListener('load', () => {
        if (window.matchMedia('(display-mode: standalone)').matches) {
          // Hide the install button when the PWA is installed
          installButton.style.display = 'none';
        }
      });
    }
  </script>



  <!-- JavaScript for local notifications with sound -->
  <script>
    const userRole = "{{ $authUserRoles[0]->roles[0]->name }}";
    const authUserId = "{{ $authUserRoles[0]->id }}";

    // console.log(authUserId)

    function playNotificationSound() {
      const audio = new Audio('/sounds/new_transaction.mp3');
      audio.play();
    }

    function displayLocalNotification(transaction) {
      // Display a local notification
      // 'Notification' in window && Notification.permission === 'granted'
      if (transaction.data.operation == 'transfert' || transaction.data.operation == 'transfert_si') {
        if ('Notification' in window && Notification.permission === 'granted') {
          const options = {
            body: transaction.data.sender_amount + ' PESOS ' + transaction.data.operation + ' by ' + transaction.agent.first_name + ' ' + transaction.agent.last_name,
            icon: '/img/logo/logo.png',
            tag: 'transaction-notification',
            badge: '/img/logo/logo.png',
            timestamp: Date.now(),
          };
          const notification = new Notification('New Transaction!', options);
          playNotificationSound(); // Play the notification sound
        }
      } else {
        if ('Notification' in window && Notification.permission === 'granted') {
          const options = {
            body: transaction.data.sender_amount + ' HTG ' + transaction.data.type + transaction.data.operation + ' by ' + transaction.agent.first_name + ' ' + transaction.agent.last_name,
            icon: '/img/logo/logo.png',
            tag: 'transaction-notification',
            badge: '/img/logo/logo.png',
            timestamp: Date.now(),
          };
          const notification = new Notification('New Transaction', options);
          playNotificationSound(); // Play the notification sound
        }
      }
    }

    // Check and request permission for notifications
    if ('Notification' in window) {
      if (Notification.permission !== 'granted') {
        Notification.requestPermission();
      }
    }

    Echo.private('new-transaction')
      .listen('NewTransactionEvent', (e) => {
        if (userRole == 'operator' && authUserId == e.operator.id || authUserId == 1 || userRole == 'system-engineer') {
          displayLocalNotification(e);
          // console.log(e)
        }
      });
  </script>
</body>

</html>