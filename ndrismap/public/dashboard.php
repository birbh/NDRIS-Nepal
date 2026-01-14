<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NDRIS-Nepal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        
        .login-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        
        .dashboard-container {
            display: none;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .dashboard-header h1 {
            font-size: 24px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-secondary:hover {
            background: #667eea;
            color: white;
        }
        
        .btn-danger {
            background: #F44336;
            color: white;
        }
        
        .btn-danger:hover {
            background: #d32f2f;
        }
        
        .dashboard-content {
            padding: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .tab {
            padding: 12px 24px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }
        
        .tab.active {
            color: #667eea;
            border-bottom-color: #667eea;
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .card h3 {
            color: #333;
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        table tr:hover {
            background: #f8f9fa;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            display: none;
        }
        
        .alert.success {
            background: #d4edda;
            color: #155724;
        }
        
        .alert.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 32px;
            margin-bottom: 8px;
            color: white;
        }
        
        .stat-card p {
            font-size: 13px;
            opacity: 0.9;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-pending {
            background: #FFC107;
            color: #333;
        }
        
        .badge-reviewed {
            background: #2196F3;
            color: white;
        }
        
        .badge-resolved {
            background: #4CAF50;
            color: white;
        }
        
        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 15px;
            }
            
            .tabs {
                flex-wrap: wrap;
            }
            
            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <!-- Login Container -->
    <div class="login-container" id="login-container">
        <div class="login-box">
            <h2>🔐 Admin Login</h2>
            <div id="login-alert" class="alert"></div>
            <form id="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            <p style="text-align: center; margin-top: 20px; color: #666; font-size: 13px;">
                Default: admin / admin123
            </p>
            <p style="text-align: center; margin-top: 10px;">
                <a href="index.php" style="color: #667eea; text-decoration: none;">← Back to Home</a>
            </p>
        </div>
    </div>
    
    <!-- Dashboard Container -->
    <div class="dashboard-container" id="dashboard-container">
        <div class="dashboard-header">
            <h1>📊 Admin Dashboard</h1>
            <div class="user-info">
                <span id="admin-username">Admin</span>
                <button onclick="logout()" class="btn btn-secondary">Logout</button>
            </div>
        </div>
        
        <div class="dashboard-content">
            <div class="stats-grid" id="stats-grid">
                <!-- Stats will be loaded dynamically -->
            </div>
            
            <div class="tabs">
                <button class="tab active" onclick="switchTab('disasters')">Disasters</button>
                <button class="tab" onclick="switchTab('grievances')">Grievances</button>
                <button class="tab" onclick="switchTab('policies')">Policies</button>
                <button class="tab" onclick="switchTab('neglect')">Neglect Index</button>
            </div>
            
            <!-- Disasters Tab -->
            <div id="disasters-tab" class="tab-content active">
                <div class="card">
                    <h3>Disaster Records</h3>
                    <button onclick="showAddForm('disaster')" class="btn btn-primary btn-sm" style="margin-bottom: 15px;">
                        + Add New Disaster
                    </button>
                    <div id="disaster-alert" class="alert"></div>
                    <div id="disaster-form" style="display:none; margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 6px;">
                        <!-- Form will be generated dynamically -->
                    </div>
                    <div style="overflow-x: auto;">
                        <table id="disasters-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>District</th>
                                    <th>Year</th>
                                    <th>Impact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Grievances Tab -->
            <div id="grievances-tab" class="tab-content">
                <div class="card">
                    <h3>Citizen Grievances</h3>
                    <div id="grievance-alert" class="alert"></div>
                    <div style="overflow-x: auto;">
                        <table id="grievances-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Category</th>
                                    <th>District</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Policies Tab -->
            <div id="policies-tab" class="tab-content">
                <div class="card">
                    <h3>Policy Records</h3>
                    <button onclick="showAddForm('policy')" class="btn btn-primary btn-sm" style="margin-bottom: 15px;">
                        + Add New Policy
                    </button>
                    <div id="policy-alert" class="alert"></div>
                    <div id="policy-form" style="display:none; margin-bottom: 20px; padding: 20px; background: #f8f9fa; border-radius: 6px;">
                        <!-- Form will be generated dynamically -->
                    </div>
                    <div style="overflow-x: auto;">
                        <table id="policies-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Policy Name</th>
                                    <th>Sector</th>
                                    <th>District</th>
                                    <th>Score</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Neglect Index Tab -->
            <div id="neglect-tab" class="tab-content">
                <div class="card">
                    <h3>District Neglect Index</h3>
                    <button onclick="recalculateAll()" class="btn btn-primary btn-sm" style="margin-bottom: 15px;">
                        🔄 Recalculate All
                    </button>
                    <div id="neglect-alert" class="alert"></div>
                    <div style="overflow-x: auto;">
                        <table id="neglect-table">
                            <thead>
                                <tr>
                                    <th>District</th>
                                    <th>Grievances</th>
                                    <th>Disasters</th>
                                    <th>Policy Score</th>
                                    <th>Neglect Score</th>
                                    <th>Last Updated</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="dashboard.js"></script>
</body>
</html>
