<nav class="main-header navbar navbar-expand navbar-dark" id="mainNavbar" style="border-bottom: 1px solid var(--glass-border); background: var(--navbar-bg);">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars text-warning"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
                Dashboard
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link text-warning font-weight-bold">
               <i class="fas fa-battery-three-quarters mr-1"></i> System Online
            </span>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <?= session()->get('email') ?> 
                <i class="far fa-user-circle ml-1 text-warning"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('/logout') ?>" style="border-left: 1px solid var(--glass-border); margin-left: 10px; padding-left: 15px;">
                <span class="text-danger"><i class="fa fa-sign-out-alt fa-fw"></i> Logout</span>
            </a>
        </li>
    </ul>
</nav>
