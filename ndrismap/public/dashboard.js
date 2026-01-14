/**
 * Dashboard JavaScript
 * NDRIS-Nepal Admin Dashboard
 */

// Check authentication on load
document.addEventListener('DOMContentLoaded', function() {
    checkAuth();
});

// Check if user is logged in
function checkAuth() {
    fetch('../php/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=check_auth'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showDashboard(data.data);
        } else {
            showLogin();
        }
    })
    .catch(() => showLogin());
}

// Show login form
function showLogin() {
    document.getElementById('login-container').style.display = 'flex';
    document.getElementById('dashboard-container').style.display = 'none';
}

// Show dashboard
function showDashboard(adminData) {
    document.getElementById('login-container').style.display = 'none';
    document.getElementById('dashboard-container').style.display = 'block';
    document.getElementById('admin-username').textContent = adminData.username;
    loadAllData();
}

// Handle login
document.getElementById('login-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'login');
    
    fetch('../php/auth.php', {
        method: 'POST',
        body: new URLSearchParams(formData)
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            showAlert('login-alert', 'error', data.message);
        }
    });
});

// Logout
function logout() {
    fetch('../php/auth.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=logout'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Switch tabs
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    event.target.classList.add('active');
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
    document.getElementById(tabName + '-tab').classList.add('active');
}

// Load all data
function loadAllData() {
    loadStats();
    loadDisasters();
    loadGrievances();
    loadPolicies();
    loadNeglectIndex();
}

// Load statistics
function loadStats() {
    Promise.all([
        fetch('../php/disaster_crud.php', {method: 'POST', body: 'action=read'}).then(r => r.json()),
        fetch('../php/grievance_crud.php', {method: 'POST', body: 'action=read'}).then(r => r.json()),
        fetch('../php/policy_crud.php', {method: 'POST', body: 'action=read'}).then(r => r.json()),
        fetch('../php/neglect_index.php', {method: 'POST', body: 'action=get_all'}).then(r => r.json())
    ]).then(results => {
        const stats = document.getElementById('stats-grid');
        stats.innerHTML = `
            <div class="stat-card">
                <h3>${results[0].data ? results[0].data.length : 0}</h3>
                <p>Total Disasters</p>
            </div>
            <div class="stat-card">
                <h3>${results[1].data ? results[1].data.length : 0}</h3>
                <p>Total Grievances</p>
            </div>
            <div class="stat-card">
                <h3>${results[2].data ? results[2].data.length : 0}</h3>
                <p>Total Policies</p>
            </div>
            <div class="stat-card">
                <h3>${results[3].data ? results[3].data.length : 0}</h3>
                <p>Districts Tracked</p>
            </div>
        `;
    });
}

// Load disasters
function loadDisasters() {
    fetch('../php/disaster_crud.php', {
        method: 'POST',
        body: 'action=read'
    })
    .then(r => r.json())
    .then(data => {
        const tbody = document.querySelector('#disasters-table tbody');
        tbody.innerHTML = '';
        
        if (data.success && data.data.length > 0) {
            data.data.forEach(disaster => {
                tbody.innerHTML += `
                    <tr>
                        <td>${disaster.id}</td>
                        <td>${disaster.title}</td>
                        <td>${disaster.type}</td>
                        <td>${disaster.district}</td>
                        <td>${disaster.year}</td>
                        <td>${disaster.impact_level}/10</td>
                        <td class="action-buttons">
                            <button class="btn btn-danger btn-sm" onclick="deleteDisaster(${disaster.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No disasters recorded</td></tr>';
        }
    });
}

// Load grievances
function loadGrievances() {
    fetch('../php/grievance_crud.php', {
        method: 'POST',
        body: 'action=read'
    })
    .then(r => r.json())
    .then(data => {
        const tbody = document.querySelector('#grievances-table tbody');
        tbody.innerHTML = '';
        
        if (data.success && data.data.length > 0) {
            data.data.forEach(grievance => {
                const statusBadge = `badge-${grievance.status}`;
                tbody.innerHTML += `
                    <tr>
                        <td>${grievance.id}</td>
                        <td>${grievance.category}</td>
                        <td>${grievance.district}</td>
                        <td>${grievance.description.substring(0, 50)}...</td>
                        <td><span class="badge ${statusBadge}">${grievance.status}</span></td>
                        <td>${new Date(grievance.created_at).toLocaleDateString()}</td>
                        <td class="action-buttons">
                            <select onchange="updateGrievanceStatus(${grievance.id}, this.value)" 
                                    style="padding: 4px; border-radius: 4px;">
                                <option value="">-- Change Status --</option>
                                <option value="pending" ${grievance.status === 'pending' ? 'selected' : ''}>Pending</option>
                                <option value="reviewed" ${grievance.status === 'reviewed' ? 'selected' : ''}>Reviewed</option>
                                <option value="resolved" ${grievance.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                            </select>
                            <button class="btn btn-danger btn-sm" onclick="deleteGrievance(${grievance.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;">No grievances submitted</td></tr>';
        }
    });
}

// Load policies
function loadPolicies() {
    fetch('../php/policy_crud.php', {
        method: 'POST',
        body: 'action=read'
    })
    .then(r => r.json())
    .then(data => {
        const tbody = document.querySelector('#policies-table tbody');
        tbody.innerHTML = '';
        
        if (data.success && data.data.length > 0) {
            data.data.forEach(policy => {
                tbody.innerHTML += `
                    <tr>
                        <td>${policy.id}</td>
                        <td>${policy.policy_name}</td>
                        <td>${policy.sector}</td>
                        <td>${policy.district}</td>
                        <td>${policy.effectiveness_score}/10</td>
                        <td class="action-buttons">
                            <button class="btn btn-danger btn-sm" onclick="deletePolicy(${policy.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No policies tracked</td></tr>';
        }
    });
}

// Load neglect index
function loadNeglectIndex() {
    fetch('../php/neglect_index.php', {
        method: 'POST',
        body: 'action=get_all'
    })
    .then(r => r.json())
    .then(data => {
        const tbody = document.querySelector('#neglect-table tbody');
        tbody.innerHTML = '';
        
        if (data.success && data.data.length > 0) {
            data.data.forEach(index => {
                tbody.innerHTML += `
                    <tr>
                        <td><strong>${index.district}</strong></td>
                        <td>${index.grievance_count}</td>
                        <td>${index.disaster_count}</td>
                        <td>${index.policy_score}</td>
                        <td><strong>${index.neglect_score}</strong></td>
                        <td>${new Date(index.last_updated).toLocaleString()}</td>
                    </tr>
                `;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No data available</td></tr>';
        }
    });
}

// Show add form
function showAddForm(type) {
    const formDiv = document.getElementById(type + '-form');
    
    if (type === 'disaster') {
        formDiv.innerHTML = `
            <h4>Add New Disaster Record</h4>
            <form id="add-disaster-form">
                <div class="form-group">
                    <label>Title*</label>
                    <input type="text" name="title" required>
                </div>
                <div class="form-group">
                    <label>Type*</label>
                    <select name="type" required>
                        <option value="earthquake">Earthquake</option>
                        <option value="flood">Flood</option>
                        <option value="landslide">Landslide</option>
                        <option value="fire">Fire</option>
                        <option value="drought">Drought</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>District*</label>
                    <input type="text" name="district" required>
                </div>
                <div class="form-group">
                    <label>Year*</label>
                    <input type="number" name="year" min="1900" max="${new Date().getFullYear()}" required>
                </div>
                <div class="form-group">
                    <label>Impact Level (1-10)*</label>
                    <input type="number" name="impact_level" min="1" max="10" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Add Disaster</button>
                <button type="button" onclick="hideForm('disaster')" class="btn btn-secondary btn-sm">Cancel</button>
            </form>
        `;
        
        document.getElementById('add-disaster-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            
            fetch('../php/disaster_crud.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showAlert('disaster-alert', 'success', data.message);
                    hideForm('disaster');
                    loadDisasters();
                    loadStats();
                } else {
                    showAlert('disaster-alert', 'error', data.message);
                }
            });
        });
    } else if (type === 'policy') {
        formDiv.innerHTML = `
            <h4>Add New Policy Record</h4>
            <form id="add-policy-form">
                <div class="form-group">
                    <label>Policy Name*</label>
                    <input type="text" name="policy_name" required>
                </div>
                <div class="form-group">
                    <label>Sector*</label>
                    <select name="sector" required>
                        <option value="health">Health</option>
                        <option value="education">Education</option>
                        <option value="infrastructure">Infrastructure</option>
                        <option value="agriculture">Agriculture</option>
                        <option value="environment">Environment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>District*</label>
                    <input type="text" name="district" required>
                </div>
                <div class="form-group">
                    <label>Effectiveness Score (1-10)*</label>
                    <input type="number" name="effectiveness_score" min="1" max="10" required>
                </div>
                <div class="form-group">
                    <label>Notes</label>
                    <textarea name="notes"></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Add Policy</button>
                <button type="button" onclick="hideForm('policy')" class="btn btn-secondary btn-sm">Cancel</button>
            </form>
        `;
        
        document.getElementById('add-policy-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'create');
            
            fetch('../php/policy_crud.php', {
                method: 'POST',
                body: new URLSearchParams(formData)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showAlert('policy-alert', 'success', data.message);
                    hideForm('policy');
                    loadPolicies();
                    loadStats();
                } else {
                    showAlert('policy-alert', 'error', data.message);
                }
            });
        });
    }
    
    formDiv.style.display = 'block';
}

function hideForm(type) {
    document.getElementById(type + '-form').style.display = 'none';
}

// Update grievance status
function updateGrievanceStatus(id, status) {
    if (!status) return;
    
    fetch('../php/grievance_crud.php', {
        method: 'POST',
        body: `action=update_status&id=${id}&status=${status}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('grievance-alert', 'success', data.message);
            loadGrievances();
        } else {
            showAlert('grievance-alert', 'error', data.message);
        }
    });
}

// Delete functions
function deleteDisaster(id) {
    if (!confirm('Are you sure you want to delete this disaster record?')) return;
    
    fetch('../php/disaster_crud.php', {
        method: 'POST',
        body: `action=delete&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('disaster-alert', 'success', data.message);
            loadDisasters();
            loadStats();
        } else {
            showAlert('disaster-alert', 'error', data.message);
        }
    });
}

function deleteGrievance(id) {
    if (!confirm('Are you sure you want to delete this grievance?')) return;
    
    fetch('../php/grievance_crud.php', {
        method: 'POST',
        body: `action=delete&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('grievance-alert', 'success', data.message);
            loadGrievances();
            loadStats();
        } else {
            showAlert('grievance-alert', 'error', data.message);
        }
    });
}

function deletePolicy(id) {
    if (!confirm('Are you sure you want to delete this policy record?')) return;
    
    fetch('../php/policy_crud.php', {
        method: 'POST',
        body: `action=delete&id=${id}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('policy-alert', 'success', data.message);
            loadPolicies();
            loadStats();
        } else {
            showAlert('policy-alert', 'error', data.message);
        }
    });
}

// Recalculate all neglect indices
function recalculateAll() {
    if (!confirm('Recalculate neglect indices for all districts?')) return;
    
    fetch('../php/neglect_index.php', {
        method: 'POST',
        body: 'action=recalculate_all'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showAlert('neglect-alert', 'success', data.message);
            loadNeglectIndex();
        } else {
            showAlert('neglect-alert', 'error', data.message);
        }
    });
}

// Show alert
function showAlert(elementId, type, message) {
    const alert = document.getElementById(elementId);
    alert.className = 'alert ' + type;
    alert.textContent = message;
    alert.style.display = 'block';
    
    setTimeout(() => {
        alert.style.display = 'none';
    }, 4000);
}
