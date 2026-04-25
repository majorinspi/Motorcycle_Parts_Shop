<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<style>
  .product-card {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(0,0,0,0.1));
  }
  .product-card:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 10px 20px rgba(255, 107, 0, 0.2);
    border-color: var(--primary-color) !important;
  }
  .cart-item {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding-bottom: 10px;
    margin-bottom: 10px;
  }
</style>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-white"><i class="fas fa-cash-register text-success mr-2"></i> Point of Sale</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Products Grid (Left) -->
        <div class="col-lg-8 mb-4">
          <div class="card glass-card border-0 bg-dark h-100">
            <div class="card-header border-0 bg-dark pb-0">
              <input type="text" id="posSearch" class="form-control bg-dark text-white border-secondary form-control-lg" placeholder="Search product by name or brand...">
            </div>
            <div class="card-body" style="height: 65vh; overflow-y: auto; overflow-x: hidden;">
              <div class="row" id="productGrid">
                <?php foreach($products as $p): ?>
                <div class="col-md-4 col-sm-6 mb-3 product-item" data-name="<?= strtolower($p['product_name']) ?>" data-brand="<?= strtolower($p['brand']) ?>">
                  <div class="card h-100 product-card border-secondary text-white" 
                       onclick="addToCart(<?= $p['product_id'] ?>, '<?= addslashes($p['product_name']) ?>', <?= $p['unit_price'] ?>, <?= $p['current_stock'] ?>)">
                    <div class="card-body p-3 text-center d-flex flex-column justify-content-center">
                      <h5 class="mb-1 font-weight-bold" style="font-size:1.05rem;"><?= esc($p['product_name']) ?></h5>
                      <p class="mb-2 text-muted small"><?= esc($p['brand']) ?></p>
                      <h4 class="text-warning mb-2 font-weight-bold">₱<?= number_format($p['unit_price'], 2) ?></h4>
                      <span class="badge badge-info p-1 align-self-center mt-auto" style="font-size:0.85rem;">Stock: <?= $p['current_stock'] ?></span>
                    </div>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Sidebar (Right) -->
        <div class="col-lg-4 mb-4">
          <div class="card glass-card border-0 bg-dark h-100">
            <div class="card-header border-0" style="background: linear-gradient(135deg, #28a745 0%, #218838 100%); opacity:0.9;">
              <h3 class="card-title text-white font-weight-bold"><i class="fas fa-shopping-cart"></i> Current Order</h3>
              <button class="btn btn-sm btn-danger float-right" onclick="clearCart()"><i class="fas fa-trash"></i> Clear</button>
            </div>
            <div class="card-body p-3 d-flex flex-column">
              <div id="cartItems" class="flex-grow-1 mb-3" style="min-height:30vh; max-height:35vh; overflow-y:auto; overflow-x:hidden;">
                <!-- Cart items will be injected here -->
                <div class="text-center text-muted mt-5" id="emptyCartMsg">
                  <i class="fas fa-cart-arrow-down fa-3x mb-3"></i>
                  <p>Cart is empty</p>
                </div>
              </div>
              
              <hr class="border-secondary mt-0">
              
              <div class="checkout-section mt-auto">
                <div class="d-flex justify-content-between mb-2">
                  <h6 class="text-light">Subtotal:</h6>
                  <h6 class="text-white" id="subtotalDisplay">₱0.00</h6>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <h4 class="text-light font-weight-bold">Total:</h4>
                  <h4 class="text-warning font-weight-bold" id="totalDisplay">₱0.00</h4>
                </div>

                <div class="form-group mb-2">
                  <select id="customerId" class="form-control bg-dark text-white border-secondary">
                    <option value="">Walk-in Customer</option>
                    <?php foreach($customers as $c): ?>
                      <option value="<?= $c['customer_id'] ?>"><?= esc($c['customer_name']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group mb-2">
                  <select id="paymentMethod" class="form-control bg-dark text-white border-secondary">
                    <option value="Cash">Cash</option>
                    <option value="Card">Card</option>
                    <option value="GCash">GCash</option>
                  </select>
                </div>
                
                <div class="form-group mb-3">
                  <input type="number" id="amountPaid" class="form-control bg-dark text-white border-secondary form-control-lg text-right text-success font-weight-bold" placeholder="Amount Paid (₱)" onkeyup="calculateChange()">
                </div>

                <div class="d-flex justify-content-between mb-3 align-items-center">
                  <h5 class="text-light m-0">Change:</h5>
                  <h4 class="text-success m-0 font-weight-bold" id="changeDisplay">₱0.00</h4>
                </div>

                <button class="btn btn-success btn-block btn-lg font-weight-bold" onclick="processCheckout()" id="checkoutBtn" disabled>
                  <i class="fas fa-check-circle"></i> Complete Checkout
                </button>
              </div>
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
  let cart = [];

  // Filter Products
  $('#posSearch').on('keyup', function() {
    let value = $(this).val().toLowerCase();
    $('.product-item').each(function() {
      let name = $(this).data('name');
      let brand = $(this).data('brand');
      if (name.includes(value) || brand.includes(value)) {
        $(this).show();
      } else {
        $(this).hide();
      }
    });
  });

  function addToCart(id, name, price, stock) {
    let existingItem = cart.find(item => item.product_id === id);
    if (existingItem) {
      if (existingItem.quantity < stock) {
        existingItem.quantity += 1;
      } else {
        toastr.warning('Maximum stock reached for this item.');
        return;
      }
    } else {
      cart.push({
        product_id: id,
        name: name,
        price: price,
        quantity: 1,
        stock: stock
      });
    }
    renderCart();
  }

  function updateQuantity(id, change) {
    let item = cart.find(i => i.product_id === id);
    if (item) {
      let newQty = item.quantity + change;
      if (newQty > 0 && newQty <= item.stock) {
        item.quantity = newQty;
      } else if (newQty > item.stock) {
        toastr.warning('Maximum stock reached.');
      }
    }
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(item => item.product_id !== id);
    renderCart();
  }

  function clearCart() {
    cart = [];
    renderCart();
  }

  function renderCart() {
    let cartHtml = '';
    let total = 0;

    if (cart.length === 0) {
      $('#emptyCartMsg').show();
      $('#checkoutBtn').prop('disabled', true);
    } else {
      $('#emptyCartMsg').hide();
      $('#checkoutBtn').prop('disabled', false);

      cart.forEach(item => {
        let subtotal = item.price * item.quantity;
        total += subtotal;
        cartHtml += `
          <div class="cart-item text-white">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="font-weight-bold" style="font-size:0.95rem;">${item.name}</span>
              <span class="text-warning font-weight-bold">₱${(item.price).toFixed(2)}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <button class="btn btn-xs btn-outline-secondary text-white" onclick="updateQuantity(${item.product_id}, -1)"><i class="fas fa-minus"></i></button>
                <span class="mx-2 font-weight-bold">${item.quantity}</span>
                <button class="btn btn-xs btn-outline-secondary text-white" onclick="updateQuantity(${item.product_id}, 1)"><i class="fas fa-plus"></i></button>
              </div>
              <div class="d-flex align-items-center">
                <span class="mr-3 font-weight-bold text-success">₱${subtotal.toFixed(2)}</span>
                <button class="btn btn-sm btn-danger rounded-circle" style="width:28px;height:28px;padding:0;" onclick="removeFromCart(${item.product_id})"><i class="fas fa-times"></i></button>
              </div>
            </div>
          </div>
        `;
      });
    }

    if (cart.length > 0) {
      $('#cartItems').html(cartHtml);
    } else {
      $('#cartItems').html('<div class="text-center text-muted mt-5" id="emptyCartMsg"><i class="fas fa-cart-arrow-down fa-3x mb-3"></i><p>Cart is empty</p></div>');
    }
    
    $('#subtotalDisplay').text('₱' + total.toFixed(2));
    $('#totalDisplay').text('₱' + total.toFixed(2));
    
    // Store total for checkout calculations
    $('#totalDisplay').data('total', total);
    calculateChange();
  }

  function calculateChange() {
    let total = parseFloat($('#totalDisplay').data('total')) || 0;
    let paid = parseFloat($('#amountPaid').val()) || 0;
    let change = paid - total;
    
    if (change >= 0) {
      $('#changeDisplay').text('₱' + change.toFixed(2)).removeClass('text-danger').addClass('text-success');
    } else {
      $('#changeDisplay').text('₱0.00').removeClass('text-success').addClass('text-danger');
    }
  }

  function processCheckout() {
    if (cart.length === 0) return;
    
    let total = parseFloat($('#totalDisplay').data('total'));
    let paid = parseFloat($('#amountPaid').val()) || 0;
    
    if (paid < total) {
      toastr.error('Amount paid is less than the total amount.');
      return;
    }

    let payload = {
      cart: JSON.stringify(cart),
      customer_id: $('#customerId').val(),
      payment_method: $('#paymentMethod').val(),
      amount_paid: paid,
      total_amount: total,
      // Pass CSRF token correctly!
      [document.querySelector('meta[name="csrf-name"]').content]: document.querySelector('meta[name="csrf-token"]').content
    };

    $('#checkoutBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    $.ajax({
      url: '<?= base_url("pos/checkout") ?>',
      type: 'POST',
      data: payload,
      dataType: 'json',
      success: function(response) {
        if (response.status === 'success') {
          toastr.success(response.message);
          cart = [];
          $('#amountPaid').val('');
          renderCart();
          // Reload page after a delay so they see success msg
          setTimeout(() => { location.reload(); }, 1500);
        } else {
          toastr.error(response.message || 'Checkout failed');
          $('#checkoutBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Complete Checkout');
        }
      },
      error: function(xhr) {
        toastr.error('Server error occurred during checkout.');
        console.error(xhr.responseText);
        $('#checkoutBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Complete Checkout');
      }
    });
  }
</script>
<?= $this->endSection() ?>
