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
              <li class="breadcrumb-item"><a href="#" class="text-warning">MotoShift</a></li>
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
            <h3 class="text-white">1,024</h3>
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
            <h3 class="text-white">₱124.5<sup style="font-size: 20px">k</sup></h3>
            <p class="text-light">Monthly Revenue</p>
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
            <h3 class="text-white">8</h3>
            <p class="text-light">Critical Low Stock</p>
          </div>
          <div class="icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
          <a href="#" class="small-box-footer text-danger bg-dark">Urgent Action <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>

    <!-- Charts and Data Row -->
    <div class="row mt-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card bg-transparent glass-card drop-shadow border-0">
                <div class="card-header border-0">
                    <h3 class="card-title text-white font-weight-bold">
                        <i class="fas fa-chart-area text-warning mr-2"></i> Revenue Overview (Last 7 Days)
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="card bg-transparent glass-card drop-shadow border-0 h-100">
                <div class="card-header border-0">
                    <h3 class="card-title text-white font-weight-bold">
                        <i class="fas fa-fire text-danger mr-2"></i> Top Selling Parts
                    </h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-white-10">
                            <div><i class="fas fa-motorcycle text-muted mr-2"></i> Brembo Brake Pads</div>
                            <span class="badge badge-success badge-pill">120 Sold</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-white-10">
                            <div><i class="fas fa-motorcycle text-muted mr-2"></i> NGK Iridium Spark Plug</div>
                            <span class="badge badge-success badge-pill">95 Sold</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-white-10">
                            <div><i class="fas fa-motorcycle text-muted mr-2"></i> Motul 300V Ester Core 4T</div>
                            <span class="badge badge-warning badge-pill">80 Sold</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-white-10">
                            <div><i class="fas fa-motorcycle text-muted mr-2"></i> K&N Air Filter</div>
                            <span class="badge badge-warning badge-pill">65 Sold</span>
                        </li>
                        <li class="list-group-item bg-transparent d-flex justify-content-between align-items-center border-0">
                            <div><i class="fas fa-motorcycle text-muted mr-2"></i> YSS Suspension G-Series</div>
                            <span class="badge badge-info badge-pill">40 Sold</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-center bg-transparent border-0">
                    <a href="#" class="text-uppercase text-warning text-sm font-weight-bold">View All Reports</a>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
</div>

<?= $this->section('scripts') ?>
<script>
$(function () {
    // Advanced Chart UI initialization
    var salesChartCanvas = $('#revenueChart').get(0).getContext('2d')
    var ctx = document.getElementById('revenueChart').getContext("2d");

    var gradientStroke = ctx.createLinearGradient(0, 0, 0, 300);
    gradientStroke.addColorStop(0, 'rgba(255, 107, 0, 0.9)');
    gradientStroke.addColorStop(1, 'rgba(255, 107, 0, 0.1)');

    var salesChartData = {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [
        {
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
        }
      ]
    }

    var salesChartOptions = {
      maintainAspectRatio: false,
      responsive: true,
      legend: { display: false },
      scales: {
        xAxes: [{
          gridLines: { display: false, color: 'rgba(255,255,255,0.1)' },
          ticks: { fontColor: '#aaa' }
        }],
        yAxes: [{
          gridLines: { display: true, color: 'rgba(255,255,255,0.05)', zeroLineColor: 'rgba(255,255,255,0.1)' },
          ticks: { fontColor: '#aaa', beginAtZero: true }
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