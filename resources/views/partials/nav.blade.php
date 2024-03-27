<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/logo.png') }}" alt="">
            <span class="d-none d-lg-block">E-OVA Trano</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->





            </li><!-- End Messages Nav -->

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2"></span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <span>Admin</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="align-middle">{{ __('Se déconnecter') }}</span>

                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="">
                <i class="bi bi-grid"></i>
                <span>Tableau de Bord</span>
            </a>
        </li><!-- End Dashboard Nav -->
            <li class="nav-item devis">
                <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-journal-text"></i><span>Devis</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li class="nav-item demande">
                        <a class="list demande" href="{{ route('devisb.attente') }}">
                            <i class="bi bi-circle"></i><span>Demande</span>
                        </a>
                    </li>
                    <li class="nav-item repondu">
                        <a  class="list repondu" href="{{ route('devisb.repondu') }}">
                            <i class="bi bi-circle"></i><span>Répondu
                        </a>
                    </li>
                    <li class="nav-item accepter">
                        <a  class="list accepter" href="{{ route('devisb.allDevisAccepter') }}">
                            <i class="bi bi-circle"></i><span>Accepter</span>
                        </a>
                    </li>
                </ul>
            </li>


        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-truck"></i><span>Livraison</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="tables-general.html">
                        <i class="bi bi-circle"></i><span>Voir</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Tables Nav -->

        <li class="nav-item equipe">
            <a class="nav-link collapsed equipe" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-person"></i><span>Equipes</span><i
                    class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse equipe" data-bs-parent="#sidebar-nav">
                <li class="nav-item list">
                    <a class="list equipe" href="{{ route('equipe.allEquipe') }}">
                        <i class="bi bi-circle"></i><span>Voir la liste</span>
                    </a>
                </li>
                <li>
                    <a class="list ajout" href="{{ route('equipe.redirection') }}">
                        <i class="bi bi-circle"></i><span>Ajouter</span>
                    </a>
                </li>

            </ul>
        </li>


            <li class="nav-item">
                <a class="nav-link collapsed categorie" data-bs-target="#categ-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-collection"></i><span>Catégories</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="categ-nav" class="nav-content collapse categorie" data-bs-parent="#sidebar-nav">
                    <li class="nav-item categorie">
                        <a class="list categorie" href="{{ route('categorie.redirection') }}">
                            <i class="bi bi-collection"></i><span>Voir</span>
                        </a>
                    </li>

                </ul>
            </li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="#">
                <i class="bi bi-bar-chart"></i><span>Statistiques</span></i>
            </a>
        </li>

    </ul>

</aside>
