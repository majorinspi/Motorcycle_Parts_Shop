<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-3 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0 text-white"><i class="fas fa-tachometer-alt text-warning mr-2"></i> Performance Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right bg-transparent p-0">
            <li class="breadcrumb-item"><a href="#" class="text-warning">Franz Cacz Motorcycle Parts and
                Accessories Shop</a></li>
            <li class="breadcrumb-item active text-muted">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <!-- Advanced Stats Row -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info glass-card border-0">
            <div class="inner">
              <h3 class="text-white"><?= isset($activeCatalogCount) ? number_format($activeCatalogCount) : '0' ?></h3>
              <p class="text-light">Active Parts Catalog</p>
            </div>
            <div class="icon">
              <i class="fas fa-cogs"></i>
            </div>
            <a href="<?= base_url('products') ?>" class="small-box-footer text-info bg-dark">Explore Catalog <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-success glass-card border-0">
            <div class="inner">
              <h3 class="text-white">₱<?= isset($totalSales) ? number_format($totalSales, 2) : '0.00' ?></h3>
              <p class="text-light">Total Sales Revenue</p>
            </div>
            <div class="icon">
              <i class="fas fa-chart-line"></i>
            </div>
            <a href="#" class="small-box-footer text-success bg-dark">View Analytics <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning glass-card border-0">
            <div class="inner">
              <h3 class="text-white">12</h3>
              <p class="text-light">Pending Restocks</p>
            </div>
            <div class="icon">
              <i class="fas fa-box-open"></i>
            </div>
            <a href="<?= base_url('suppliers') ?>" class="small-box-footer text-warning bg-dark">Manage Orders <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box bg-danger glass-card border-0">
            <div class="inner">
              <h3 class="text-white"><?= isset($criticalLowStockCount) ? number_format($criticalLowStockCount) : '0' ?></h3>
              <p class="text-light">Critical Low Stock</p>
            </div>
            <div class="icon">
              <i class="fas fa-exclamation-triangle"></i>
            </div>
            <a href="<?= base_url('products') ?>" class="small-box-footer text-danger bg-dark">Urgent Action <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>





      <?= $this->section('scripts') ?>
      <script>
        $(function() {
          // Advanced Chart UI initialization
          var salesChartCanvas = $('#revenueChart').get(0).getContext('2d')
          var ctx = document.getElementById('revenueChart').getContext("2d");

          var gradientStroke = ctx.createLinearGradient(0, 0, 0, 300);
          gradientStroke.addColorStop(0, 'rgba(255, 107, 0, 0.9)');
          gradientStroke.addColorStop(1, 'rgba(255, 107, 0, 0.1)');

          var salesChartData = {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
              label: 'Revenue (₱)',
              backgroundColor: gradientStroke,
              borderColor: '#ff6b00',
              pointRadius: 4,
              pointBackgroundColor: '#ff6b00',
              pointBorderColor: '#fff',
              pointHoverRadius: 6,
              pointHoverBackgroundColor: '#fff',
              pointHoverBorderColor: '#ff6b00',
              data: [15000, 22000, 18500, 30000, 28000, 45000, 38000],
              fill: true
            }]
          }

          var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
              display: false
            },
            scales: {
              xAxes: [{
                gridLines: {
                  display: false,
                  color: 'rgba(255,255,255,0.1)'
                },
                ticks: {
                  fontColor: '#aaa'
                }
              }],
              yAxes: [{
                gridLines: {
                  display: true,
                  color: 'rgba(255,255,255,0.05)',
                  zeroLineColor: 'rgba(255,255,255,0.1)'
                },
                ticks: {
                  fontColor: '#aaa',
                  beginAtZero: true
                }
              }]
            }
          }

          new Chart(salesChartCanvas, {
            type: 'line',
            data: salesChartData,
            options: salesChartOptions
          })
        })
      </script>
      <?= $this->endSection() ?>
      <?= $this->endSection() ?>