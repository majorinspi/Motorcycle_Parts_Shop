<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Customer Records</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard v1</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Customers</h3>
              <div class="float-right">
                <button type="button" class="btn btn-md btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle fa fw"></i> Add New
                </button>
              </div>
            </div>
            <div class="card-body">
               <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">customer_id</th>
                    <th>Customer Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ✅ Add New Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1" role="dialog" aria-labelledby="AddNewModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form id="addCustomerForm">
            <?= csrf_field() ?>
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle fa fw"></i>  Add New</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="customer_name" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="contact_number" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" required />
              </div>
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fas fa-times-circle'></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>

<div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="editCustomerForm">
          <?= csrf_field() ?>
          <div class="modal-header">
            <h5 class="modal-title" id="editCustomerModalLabel"><i class="far fa-edit fa fw"></i> Edit Record</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" id="customer_id" name="customer_id">
            <div class="form-group">
              <label>Customer Name</label>
              <input type="text" name="customer_name" id="customer_name" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Contact Number</label>
              <input type="text" name="contact_number" id="contact_number" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="text" name="address" id="address" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" id="email" class="form-control" required />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class='fas fa-times-circle'></i> Cancel</button>
            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
</div>
<div class="toasts-top-right fixed" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;"></div>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script> const baseUrl = "<?= base_url() ?>"; </script>
<script src="<?= base_url('js/customers/customers.js') ?>"></script>
<?= $this->endSection() ?>
