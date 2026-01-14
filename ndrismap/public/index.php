<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NDRIS-Nepal - National Disaster, Responsibility & Impact System</title>
    <link rel="stylesheet" href="../map/map.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        nav {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 2px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        nav a {
            color: #667eea;
            text-decoration: none;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        nav a:hover {
            background: #667eea;
            color: white;
        }
        
        nav a.active {
            background: #667eea;
            color: white;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .intro-section {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .intro-section h2 {
            color: #333;
            margin-bottom: 15px;
        }
        
        .intro-section p {
            color: #666;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .map-container {
            margin: 30px 0;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 36px;
            margin-bottom: 10px;
        }
        
        .stat-card p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 2px solid #e0e0e0;
        }
        
        @media (max-width: 768px) {
            header h1 {
                font-size: 24px;
            }
            
            nav {
                flex-direction: column;
                text-align: center;
            }
            
            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🗺️ NDRIS-Nepal</h1>
            <p>National Disaster, Responsibility & Impact System</p>
        </header>
        
        <nav>
            <div>
                <a href="index.php" class="active">Home</a>
                <a href="report.php">Submit Grievance</a>
                <a href="#about">About</a>
            </div>
            <div>
                <a href="dashboard.php">Admin Dashboard</a>
            </div>
        </nav>
        
        <div class="content">
            <div class="intro-section">
                <h2>Interactive Nepal Map</h2>
                <p>
                    Explore district-wise data on disasters, citizen grievances, policy implementation, 
                    and urban neglect indices. Click on any district to view detailed information.
                </p>
            </div>
            
            <div class="map-container">
                <object data="../map/nepal.svg" type="image/svg+xml" id="map-object" 
                        style="width: 100%; max-width: 800px; height: auto;">
                    Your browser does not support SVG
                </object>
            </div>
            
            <div class="stats-grid" id="stats-grid">
                <div class="stat-card">
                    <h3 id="total-disasters">...</h3>
                    <p>Total Disasters Recorded</p>
                </div>
                <div class="stat-card">
                    <h3 id="total-grievances">...</h3>
                    <p>Citizen Grievances</p>
                </div>
                <div class="stat-card">
                    <h3 id="total-policies">...</h3>
                    <p>Policies Tracked</p>
                </div>
                <div class="stat-card">
                    <h3 id="districts-analyzed">...</h3>
                    <p>Districts Analyzed</p>
                </div>
            </div>
            
            <div id="about" style="margin-top: 50px; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                <h2 style="color: #333; margin-bottom: 15px;">About NDRIS-Nepal</h2>
                <p style="color: #666; line-height: 1.8;">
                    NDRIS-Nepal is a civic-tech platform that integrates disaster memory records, 
                    citizen grievances, policy impact tracking, and urban neglect indices into a 
                    single interactive system. The platform aims to promote transparency, data-driven 
                    governance, and public accountability across Nepal's districts. This is a 
                    functional prototype designed for educational and civic engagement purposes.
                </p>
            </div>
        </div>
        
        <footer>
            <p>&copy; 2026 NDRIS-Nepal Project | Developed for Academic & Civic Purposes</p>
            <p style="font-size: 12px; margin-top: 5px;">
                Data is heuristic and for demonstration purposes only
            </p>
        </footer>
    </div>
    
    <script>
        // Wait for SVG to load
        document.getElementById('map-object').addEventListener('load', function() {
            const svgDoc = this.contentDocument;
            if (svgDoc) {
                // Inject map.js functionality into SVG
                const script = svgDoc.createElement('script');
                script.src = '../map/map.js';
                svgDoc.documentElement.appendChild(script);
                
                // Also inject CSS
                const link = svgDoc.createElement('link');
                link.rel = 'stylesheet';
                link.href = '../map/map.css';
                svgDoc.documentElement.appendChild(link);
            }
        });
        
        // Load statistics
        function loadStatistics() {
            // Load disasters count
            fetch('../php/disaster_crud.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=read'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total-disasters').textContent = data.data.length;
                }
            });
            
            // Load grievances count
            fetch('../php/grievance_crud.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=read'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total-grievances').textContent = data.data.length;
                }
            });
            
            // Load policies count
            fetch('../php/policy_crud.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=read'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('total-policies').textContent = data.data.length;
                }
            });
            
            // Load districts count
            fetch('../php/neglect_index.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=get_all'
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('districts-analyzed').textContent = data.data.length;
                }
            });
        }
        
        // Load stats on page load
        document.addEventListener('DOMContentLoaded', loadStatistics);
    </script>
</body>
</html>
