<aside class="main-sidebar sidebar-dark-primary elevation-5" id="mainSidebar">
<div class="brand-link" id="brandLink">
    <span class="brand-text ml-3" style="font-size: 0.9rem; line-height: 1.2; display: flex; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <i class="fas fa-motorcycle text-warning"></i>
        <span>Franz Cacz Motorcycle<br/>Parts & Accessories Shop</span>
    </span>
</div>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= base_url('dashboard') ?>" class="nav-link <?= is_active(1, 'dashboard') ?>">
            <i class="nav-icon fas fa-tachometer-alt text-primary"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- POS -->
        <li class="nav-item">
          <a href="<?= base_url('pos') ?>" class="nav-link <?= is_active(1, 'pos') ?>">
            <i class="nav-icon fas fa-cash-register text-success"></i>
            <p>Point of Sale (POS)</p>
          </a>
        </li>

        <!-- Customers -->
        <li class="nav-item">
          <a href="<?= base_url('customers') ?>" class="nav-link <?= is_active(1, 'customers') ?>">
            <i class="nav-icon fas fa-users text-info"></i>
            <p>Customers</p>
          </a>
        </li>

        <!-- Product Categories -->
        <li class="nav-item">
          <a href="<?= base_url('categories') ?>" class="nav-link <?= is_active(1, 'categories') ?>">
            <i class="nav-icon fas fa-list-ul text-warning"></i>
            <p>Product Categories</p>
          </a>
        </li>

        <!-- Supplier Records -->
        <li class="nav-item">
          <a href="<?= base_url('suppliers') ?>" class="nav-link <?= is_active(1, 'suppliers') ?>">
            <i class="nav-icon fas fa-dolly text-orange"></i>
            <p>Supplier Records</p>
          </a>
        </li>

        <!-- Product Records -->
        <li class="nav-item">
          <a href="<?= base_url('products') ?>" class="nav-link <?= is_active(1, 'products') ?>">
            <i class="nav-icon fas fa-clipboard-list text-purple"></i>
            <p>Product List</p>
          </a>
        </li>

        <!-- Transaction Records -->
        <li class="nav-item">
          <a href="<?= base_url('transactions') ?>" class="nav-link <?= is_active(1, 'transactions') ?>">
            <i class="nav-icon fas fa-file-invoice-dollar text-danger"></i>
            <p>Transaction Records</p>
          </a>
        </li>

        <!-- User Accounts -->
        <li class="nav-item">
          <a href="<?= base_url('users') ?>" class="nav-link <?= is_active(1, 'users') ?>">
            <i class="nav-icon fas fa-user-lock text-secondary"></i>
            <p>User Accounts</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>