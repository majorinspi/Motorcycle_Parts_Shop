<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 text-white"><i class="fas fa-boxes text-info mr-2"></i> Monthly Inventory (<?= esc($currentMonthName) ?>)</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right bg-transparent p-0">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>" class="text-info">Dashboard</a></li>
            <li class="breadcrumb-item active text-muted">Monthly Inventory</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        
        <!-- Sold Items Card -->
        <div class="col-12">
          <div class="card glass-card border-0 mb-4 bg-dark">
            <div class="card-header border-0" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%);">
              <h3 class="card-title text-white"><i class="fas fa-check-circle mr-1"></i> Sold Items This Month</h3>
            </div>
            <div class="card-body text-white">
              <table id="soldItemsTable" class="table table-bordered table-striped table-sm text-white">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Quantity Sold</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach($soldItems as $item): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['brand']) ?></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td><span class="badge badge-info p-1"><?= esc($item['current_stock']) ?></span></td>
                    <td><span class="badge badge-success p-1"><?= esc($item['total_sold']) ?></span></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Unsold Items Card -->
        <div class="col-12">
          <div class="card glass-card border-0 bg-dark">
            <div class="card-header border-0" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
              <h3 class="card-title text-white"><i class="fas fa-times-circle mr-1"></i> Unsold Items This Month</h3>
            </div>
            <div class="card-body text-white">
              <table id="unsoldItemsTable" class="table table-bordered table-striped table-sm text-white">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Product Name</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i=1; foreach($unsoldItems as $item): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['brand']) ?></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td>
                      <?php if($item['current_stock'] <= 10): ?>
                        <span class="badge badge-danger p-1"><?= esc($item['current_stock']) ?> (Low)</span>
                      <?php else: ?>
                        <span class="badge badge-secondary p-1"><?= esc($item['current_stock']) ?></span>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#soldItemsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
    });
    $('#unsoldItemsTable').DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });
</script>
<?= $this->endSection() ?>
