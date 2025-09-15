<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pembayaran Laundry</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f1f1f1;
    }
    .main-content {
      padding: 20px;
      margin-top: 0;
    }
    .payment-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      padding: 25px;
      font-size: 0.9rem;
    }
    .payment-header {
      font-size: 1rem;
      font-weight: bold;
      background-color: #f71f8f;
      color: white;
      padding: 10px 15px;
      border-radius: 10px;
      margin-bottom: 25px;
    }
    .detail-label {
      width: 150px;
      color: #6c757d;
    }
    .detail-row {
      display: flex;
      margin-bottom: 10px;
    }
    .detail-value {
      font-weight: 500;
      margin-left: 80px; /* Added margin to push text to the right */
    }
    .detail-value2 {
      font-weight: bold;
      margin-left: 80px; /* Added margin to push text to the right */
    }
    .confirm-btn {
      background-color: #f71f8f;
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 20px;
      font-weight: bold;
      cursor: pointer;
    }
    .confirm-btn:hover {
      background-color: #e0157f;
    }
    .payment-methods img {
      width: 50px;
    }
    .payment-method-box {
      background-color: #ffe5f0;
      border-radius: 10px;
      padding: 20px;
      margin-top: 10px;
    }
    .dropdown-toggle::after {
      float: right;
      margin-top: 8px;
    }
  </style>
</head>
<body>

  <!-- Main Content -->
  <div class="main-content">
    <h5 style="font-size: 1.4rem;"><strong><i class="bi bi-credit-card"></i> Pembayaran Laundry</strong></h5>

    <div class="payment-card mt-3">
      <div class="payment-header">Pembayaran</div>

      <div class="mb-4">
        <h6 class="fw-bold">Detail Pembayaran</h6>
        <div class="detail-row">
          <div class="detail-label">Status</div>
          <div class="detail-value">Belum Dibayar</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">ID Pembayaran</div>
          <div class="detail-value">TBO02</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">ID Transaksi</div>
          <div class="detail-value2">TRX-2023-002</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Tanggal</div>
          <div class="detail-value">16 Jan 2023</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Total</div>
          <div class="detail-value text-danger">Rp 65.000</div>
        </div>
      </div>

      <hr />
      <div class="mb-4">
        <h6 class="fw-bold">Metode Pembayaran</h6>
        <select class="form-select mb-3" id="paymentMethod" style="width: 250px; background-color: #ffe5f0;">
          <option value="tunai" selected>Tunai</option>
          <option value="transfer">Transfer</option>
          <option value="qris">QRIS</option>
        </select>
      </div>

      <div class="payment-method-box" id="bankTransferBox" style="width: 250px; padding: 15px;">
        <div class="payment-methods">
          <h6 class="fw-bold mb-3">Bank Transfer</h6>

          <!-- BCA -->
          <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('images/logobca.jpg') }}" alt="BCA" class="me-3">
            <div>
              <p class="mb-0 fw-bold">BCA</p>
              <p class="mb-0 small">1234567890 (Istana Laundry)</p>
            </div>
          </div>

          <!-- BRI -->
          <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('images/logobri.jpg') }}" alt="BRI" class="me-3">
            <div>
              <p class="mb-0 fw-bold">BRI</p>
              <p class="mb-0 small">9876543210 (Istana Laundry)</p>
            </div>
          </div>

          <!-- BNI -->
          <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('images/logobni.jpg') }}" alt="BNI" class="me-3">
            <div>
              <p class="mb-0 fw-bold">BNI</p>
              <p class="mb-0 small">04787789 (Istana Laundry)</p>
            </div>
          </div>
        </div>
      </div>
      <div class="text-end mt-4">
        <button class="confirm-btn">
          <i class="bi bi-check-circle me-1"></i> Konfirmasi Pembayaran
        </button>
      </div>
    </div>
  </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const paymentMethod = document.getElementById('paymentMethod');
      const bankTransferBox = document.getElementById('bankTransferBox');
      
      // Fungsi untuk menampilkan/sembunyikan bank transfer
      function toggleBankTransfer() {
        if (paymentMethod.value === 'transfer') {
          bankTransferBox.style.display = 'block';
        } else {
          bankTransferBox.style.display = 'none';
        }
      }
      
      // Panggil fungsi saat halaman dimuat
      toggleBankTransfer();
      
      // Tambahkan event listener untuk perubahan select
      paymentMethod.addEventListener('change', toggleBankTransfer);
    });
  </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>