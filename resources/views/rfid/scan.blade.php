<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Absensi Driver AMT - Simple AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        .scan-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }

        .rfid-input {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 5px;
            text-align: center;
            outline: none;
        }

        .rfid-input:focus {
            border-color: #007bff;
        }

        .alert {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-weight: bold;
        }

        .alert.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }

        .stat-card {
            text-align: center;
            background: #007bff;
            color: white;
            padding: 20px;
            border-radius: 8px;
            min-width: 120px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
        }

        .stat-label {
            font-size: 12px;
            margin-top: 5px;
        }

        .table-section {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd;
        }

        .table-header {
            background: #343a40;
            color: white;
            padding: 15px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
        }

        .loading {
            display: none;
            text-align: center;
            color: #007bff;
            margin: 10px 0;
        }

        .status-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="status-indicator" title="Auto-update aktif"></div>
    
    <div class="container">
        <h1>Absensi Driver AMT</h1>

        <div class="scan-section">
            <!-- Alert container untuk pesan AJAX -->
            <div id="alertContainer">
                @if (session('success'))
                    <div class="alert success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert error">{{ session('error') }}</div>
                @endif
            </div>

            <form id="rfidForm" method="POST" action="{{ route('rfid.scan') }}">
                @csrf
                <input type="text" 
                       id="rfid" 
                       name="rfid" 
                       class="rfid-input" 
                       placeholder="Scan RFID di sini..." 
                       autofocus
                       autocomplete="off">
            </form>
            
            <div class="loading" id="loading">
                Memproses scan RFID...
            </div>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="stat-number" id="totalToday">{{ count($today) }}</div>
                <div class="stat-label">TOTAL HARI INI</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="currentTime">--:--:--</div>
                <div class="stat-label">WAKTU SEKARANG</div>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header">
                <h3>Daftar Absensi Hari Ini</h3>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody id="attendanceTable">
                    @forelse ($today as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->driverAmt->name }}</td>
                            <td>{{ $item->driverAmt->position }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->scanned_at)->format('H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="no-data">Belum ada absensi hari ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Global variables
        let autoUpdateInterval;
        let isProcessing = false;

        // Initialize app
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });

        function initializeApp() {
            // Focus pada input RFID
            document.getElementById('rfid').focus();
            
            // Setup RFID form handler
            setupRFIDHandler();
            
            // Start auto-update
            startAutoUpdate();
            
            // Update waktu real-time
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
            
            // Auto-focus input ketika user klik di mana saja
            document.addEventListener('click', function(e) {
                if (!e.target.closest('table')) {
                    document.getElementById('rfid').focus();
                }
            });
        }

        function setupRFIDHandler() {
            const form = document.getElementById('rfidForm');
            const rfidInput = document.getElementById('rfid');
            
            // Handle form submit
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (isProcessing) return;
                
                const rfidValue = rfidInput.value.trim();
                if (rfidValue.length < 1) return;
                
                submitRFIDScan(rfidValue);
            });
            
            // Auto-submit ketika input selesai (dengan delay)
            let inputTimeout;
            rfidInput.addEventListener('input', function() {
                clearTimeout(inputTimeout);
                
                const value = this.value.trim();
                if (value.length >= 3) { // Minimal 3 karakter
                    inputTimeout = setTimeout(() => {
                        if (!isProcessing) {
                            form.dispatchEvent(new Event('submit'));
                        }
                    }, 500); // Delay 500ms
                }
            });
        }

        function submitRFIDScan(rfidValue) {
            if (isProcessing) return;
            
            isProcessing = true;
            
            // Show loading
            document.getElementById('loading').style.display = 'block';
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // AJAX request
            fetch('{{ route("rfid.scan") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    rfid: rfidValue
                })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading
                document.getElementById('loading').style.display = 'none';
                
                // Clear input
                document.getElementById('rfid').value = '';
                
                // Show message
                showAlert(data.message, data.success ? 'success' : 'error');
                
                // Update data jika berhasil
                if (data.success) {
                    updateAttendanceData();
                }
                
                // Re-focus input
                setTimeout(() => {
                    document.getElementById('rfid').focus();
                }, 100);
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Hide loading
                document.getElementById('loading').style.display = 'none';
                
                // Show error
                showAlert('Terjadi kesalahan sistem. Silakan coba lagi.', 'error');
                
                // Clear input and re-focus
                document.getElementById('rfid').value = '';
                document.getElementById('rfid').focus();
            })
            .finally(() => {
                isProcessing = false;
            });
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            
            // Remove existing alerts
            const existingAlerts = alertContainer.querySelectorAll('.alert');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new alert
            const alert = document.createElement('div');
            alert.className = `alert ${type}`;
            alert.textContent = message;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 3000);
        }

        function updateAttendanceData() {
            fetch('{{ route("rfid.data") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update table
                    updateTable(data.data);
                    
                    // Update counter
                    document.getElementById('totalToday').textContent = data.total;
                }
            })
            .catch(error => {
                console.log('Auto-update error:', error);
            });
        }

        function updateTable(data) {
            const tbody = document.getElementById('attendanceTable');
            
            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="no-data">Belum ada absensi hari ini</td></tr>';
            } else {
                tbody.innerHTML = data.map(item => `
                    <tr>
                        <td>${item.no}</td>
                        <td>${item.name}</td>
                        <td>${item.position}</td>
                        <td>${item.time}</td>
                    </tr>
                `).join('');
            }
        }

        function startAutoUpdate() {
            // Update setiap 10 detik
            autoUpdateInterval = setInterval(() => {
                updateAttendanceData();
            }, 10000);
        }

        function updateCurrentTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Cleanup
        window.addEventListener('beforeunload', function() {
            if (autoUpdateInterval) {
                clearInterval(autoUpdateInterval);
            }
        });
    </script>
</body>
</html>