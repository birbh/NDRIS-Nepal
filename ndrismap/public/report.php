<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Grievance - NDRIS-Nepal</title>
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
            max-width: 800px;
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
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        nav {
            background: #f8f9fa;
            padding: 15px 30px;
            border-bottom: 2px solid #e0e0e0;
            text-align: center;
        }
        
        nav a {
            color: #667eea;
            text-decoration: none;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 4px;
            font-weight: 500;
            transition: all 0.3s;
            display: inline-block;
        }
        
        nav a:hover {
            background: #667eea;
            color: white;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none;
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
        
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 4px;
        }
        
        .info-box p {
            color: #0d47a1;
            line-height: 1.6;
        }
        
        footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
            border-top: 2px solid #e0e0e0;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>📝 Submit a Grievance</h1>
            <p>Report issues and concerns in your district</p>
        </header>
        
        <nav>
            <a href="index.php">Home</a>
            <a href="report.php" style="background: #667eea; color: white;">Submit Grievance</a>
            <a href="dashboard.php">Admin Dashboard</a>
        </nav>
        
        <div class="content">
            <div class="info-box">
                <p>
                    <strong>Your voice matters.</strong> Use this form to report infrastructure issues, 
                    health concerns, educational needs, or any governance-related problems in your district. 
                    All submissions are reviewed by administrators.
                </p>
            </div>
            
            <div id="alert-box" class="alert"></div>
            
            <form id="grievance-form">
                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="infrastructure">Infrastructure</option>
                        <option value="health">Health</option>
                        <option value="education">Education</option>
                        <option value="water">Water Supply</option>
                        <option value="electricity">Electricity</option>
                        <option value="sanitation">Sanitation</option>
                        <option value="transportation">Transportation</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="district">District *</label>
                    <select id="district" name="district" required>
                        <option value="">-- Select District --</option>
                        <option value="Kathmandu">Kathmandu</option>
                        <option value="Lalitpur">Lalitpur</option>
                        <option value="Bhaktapur">Bhaktapur</option>
                        <option value="Pokhara">Pokhara (Kaski)</option>
                        <option value="Kaski">Kaski</option>
                        <option value="Gorkha">Gorkha</option>
                        <option value="Dhading">Dhading</option>
                        <option value="Chitwan">Chitwan</option>
                        <option value="Morang">Morang</option>
                        <option value="Sunsari">Sunsari</option>
                        <option value="Rupandehi">Rupandehi</option>
                        <option value="Banke">Banke</option>
                        <option value="Dang">Dang</option>
                        <option value="Jajarkot">Jajarkot</option>
                        <option value="Kailali">Kailali</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" 
                              placeholder="Please describe the issue in detail (minimum 10 characters)..."
                              required></textarea>
                </div>
                
                <button type="submit" class="btn" id="submit-btn">Submit Grievance</button>
            </form>
        </div>
        
        <footer>
            <p>&copy; 2026 NDRIS-Nepal Project | Your feedback helps improve governance</p>
        </footer>
    </div>
    
    <script>
        const form = document.getElementById('grievance-form');
        const alertBox = document.getElementById('alert-box');
        const submitBtn = document.getElementById('submit-btn');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(form);
            formData.append('action', 'create');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            
            // Send AJAX request
            fetch('../php/grievance_crud.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Grievance submitted successfully! Thank you for your feedback.');
                    form.reset();
                } else {
                    showAlert('error', data.message || 'Failed to submit grievance. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'An error occurred. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit Grievance';
            });
        });
        
        function showAlert(type, message) {
            alertBox.className = 'alert ' + type;
            alertBox.textContent = message;
            alertBox.style.display = 'block';
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
            
            // Scroll to alert
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    </script>
</body>
</html>
