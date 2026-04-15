<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>: Franz Cacz Motorcycle Parts and
Accessories Shop| Premium Parts Management</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <style>
    :root {
      --primary-color: #ff6b00;
      --primary-hover: #e05e00;
      --bg-gradient-start: #0f1115;
      --bg-gradient-end: #1c1f26;
      --glass-bg: rgba(30, 34, 43, 0.6);
      --glass-border: rgba(255, 255, 255, 0.08);
      --text-color: #f1f1f1;
      --input-bg: rgba(0, 0, 0, 0.2);
    }
    
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
    }

    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: radial-gradient(circle at top right, #2a2d34, var(--bg-gradient-start));
      color: var(--text-color);
      overflow: hidden;
      position: relative;
    }

    /* Dynamic background accent */
    body::before {
      content: '';
      position: absolute;
      top: -20%;
      left: -10%;
      width: 50vw;
      height: 50vw;
      background: radial-gradient(circle, rgba(255, 107, 0, 0.1) 0%, transparent 70%);
      border-radius: 50%;
      z-index: 1;
      animation: float 10s ease-in-out infinite alternate;
    }

    @keyframes float {
      0% { transform: translateY(0) scale(1); }
      100% { transform: translateY(20px) scale(1.05); }
    }

    .login-container {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 420px;
      padding: 2.5rem;
      background: var(--glass-bg);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.8s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .logo-area {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-area h1 {
      font-size: 2rem;
      font-weight: 700;
      background: linear-gradient(135deg, #fff, #aaa);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }

    .logo-area p {
      font-size: 0.9rem;
      color: #9ea3b0;
    }

    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }

    .form-group i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #7b8191;
      font-size: 1.1rem;
      transition: color 0.3s;
    }

    .form-control {
      width: 100%;
      padding: 14px 15px 14px 45px;
      background: var(--input-bg);
      border: 1px solid var(--glass-border);
      border-radius: 12px;
      font-size: 1rem;
      color: var(--text-color);
      transition: all 0.3s ease;
      outline: none;
    }

    .form-control::placeholder {
      color: #6a7080;
    }

    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(255, 107, 0, 0.15);
      background: rgba(0, 0, 0, 0.3);
    }

    .form-control:focus + i, 
    .form-control:not(:placeholder-shown) + i {
      color: var(--primary-color);
    }

    .btn-primary {
      width: 100%;
      padding: 14px;
      background: var(--primary-color);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      box-shadow: 0 8px 16px rgba(255, 107, 0, 0.3);
    }

    .btn-primary:hover:not(:disabled) {
      background: var(--primary-hover);
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(255, 107, 0, 0.4);
    }

    .btn-primary:disabled {
      background: #555;
      cursor: not-allowed;
      box-shadow: none;
    }

    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      font-size: 0.9rem;
    }

    .checkbox-container {
      display: flex;
      align-items: center;
      cursor: pointer;
      color: #9ea3b0;
    }

    .checkbox-container input {
      margin-right: 8px;
      accent-color: var(--primary-color);
      width: 16px;
      height: 16px;
    }

    .alert {
      padding: 12px 15px;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      font-size: 0.9rem;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-danger {
      background: rgba(231, 76, 60, 0.15);
      border: 1px solid rgba(231, 76, 60, 0.3);
      color: #ff6b6b;
    }

    .alert-warning {
      background: rgba(241, 196, 15, 0.15);
      border: 1px solid rgba(241, 196, 15, 0.3);
      color: #f1c40f;
    }

    .alert-success {
      background: rgba(46, 204, 113, 0.15);
      border: 1px solid rgba(46, 204, 113, 0.3);
      color: #2ecc71;
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="logo-area">
    <h1>Franz Cacz Motorcycle Parts and
Accessories Shop</h1>
    <p>Premium Parts Management</p>
  </div>

  <?php $lockoutTime = $lockout ?? 0; ?>

  <?php if ($lockoutTime > 0): ?>
    <div class="alert alert-warning" id="lockout-alert">
      <i class="fas fa-exclamation-triangle"></i>
      <div>
        <strong>Too many attempts.</strong><br>
        Wait <span id="lockout-timer"></span> to try again.
      </div>
    </div>
  <?php elseif (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
      <i class="fas fa-times-circle"></i>
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>

  <form action="<?= base_url('/auth') ?>" method="post">
    <?= csrf_field() ?>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="Email Address" required>
      <i class="fas fa-envelope"></i>
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="Password" required>
      <i class="fas fa-lock"></i>
    </div>

    <div class="options">
      <label class="checkbox-container">
        <input type="checkbox" name="remember" id="remember"> Remember Me
      </label>
    </div>

    <button type="submit" class="btn-primary" id="signInBtn" <?= ($lockoutTime > 0) ? 'disabled' : '' ?>>
      <span>Sign In to Dashboard</span>
      <i class="fas fa-arrow-right"></i>
    </button>
  </form>
</div>

<?php if ($lockoutTime > 0): ?>
<script>
  let secondsLeft = <?= $lockoutTime ?>;
  const timerDisplay = document.getElementById('lockout-timer');
  const signInBtn = document.getElementById('signInBtn');
  const alertBox = document.getElementById('lockout-alert');

  function updateTimer() {
    if (secondsLeft > 0) {
      let minutes = Math.floor(secondsLeft / 60);
      let seconds = secondsLeft % 60;
      timerDisplay.textContent = `${minutes}:${(seconds < 10 ? '0' : '')}${seconds}`;
      secondsLeft--;
      setTimeout(updateTimer, 1000);
    } else {
      signInBtn.disabled = false;
      alertBox.classList.remove('alert-warning');
      alertBox.classList.add('alert-success');
      alertBox.innerHTML = `<i class="fas fa-check-circle"></i> <div><strong>Ready to login.</strong></div>`;
    }
  }

  updateTimer();
</script>
<?php endif; ?>
</body>
</html>
