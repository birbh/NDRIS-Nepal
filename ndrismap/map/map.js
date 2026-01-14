/**
 * Interactive Map JavaScript
 * NDRIS-Nepal System
 * 
 * Handles district click interactions and data visualization
 */

// Global variables
let districtData = {};
let currentDistrict = null;

/**
 * Initialize map after DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeMap();
    loadAllDistrictData();
});

/**
 * Initialize map interactions
 */
function initializeMap() {
    const districts = document.querySelectorAll('.district, #Pokhara');
    
    districts.forEach(district => {
        // Add hover effect
        district.addEventListener('mouseenter', function() {
            this.style.opacity = '0.7';
            this.style.cursor = 'pointer';
        });
        
        district.addEventListener('mouseleave', function() {
            this.style.opacity = '1';
        });
        
        // Add click event
        district.addEventListener('click', function() {
            const districtName = this.getAttribute('data-district');
            if (districtName) {
                selectDistrict(districtName);
            }
        });
    });
}

/**
 * Load data for all districts and color the map
 */
function loadAllDistrictData() {
    fetch('../php/neglect_index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=get_all'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            // Store district data
            data.data.forEach(district => {
                districtData[district.district] = district;
            });
            
            // Color the map based on neglect scores
            colorMap();
        }
    })
    .catch(error => {
        console.error('Error loading district data:', error);
    });
}

/**
 * Color map districts based on neglect scores
 */
function colorMap() {
    Object.keys(districtData).forEach(districtName => {
        const districtElement = document.querySelector(`[data-district="${districtName}"]`);
        if (districtElement) {
            const score = districtData[districtName].neglect_score;
            const color = getNeglectColor(score);
            districtElement.setAttribute('fill', color);
        }
    });
}

/**
 * Get color based on neglect score
 * @param {number} score - Neglect score
 * @returns {string} - Hex color code
 */
function getNeglectColor(score) {
    if (score < 5) {
        return '#4CAF50'; // Green - low neglect
    } else if (score < 10) {
        return '#FFC107'; // Yellow - medium neglect
    } else {
        return '#F44336'; // Red - high neglect
    }
}

/**
 * Select a district and display its information
 * @param {string} districtName - Name of the district
 */
function selectDistrict(districtName) {
    currentDistrict = districtName;
    
    // Highlight selected district
    highlightDistrict(districtName);
    
    // Fetch detailed data for the district
    fetchDistrictDetails(districtName);
}

/**
 * Highlight selected district on map
 * @param {string} districtName - District to highlight
 */
function highlightDistrict(districtName) {
    // Remove previous highlights
    document.querySelectorAll('.district, #Pokhara').forEach(d => {
        d.style.strokeWidth = '1';
        d.style.stroke = '#333';
    });
    
    // Highlight selected district
    const districtElement = document.querySelector(`[data-district="${districtName}"]`);
    if (districtElement) {
        districtElement.style.strokeWidth = '3';
        districtElement.style.stroke = '#000';
    }
}

/**
 * Fetch detailed district information
 * @param {string} districtName - District name
 */
function fetchDistrictDetails(districtName) {
    fetch('../php/neglect_index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=district_summary&district=${encodeURIComponent(districtName)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data) {
            displayDistrictInfo(data.data);
        } else {
            showError('Failed to load district data');
        }
    })
    .catch(error => {
        console.error('Error fetching district details:', error);
        showError('Error loading district information');
    });
}

/**
 * Display district information in the info panel
 * @param {object} data - District data object
 */
function displayDistrictInfo(data) {
    const infoPanel = document.getElementById('district-info');
    if (!infoPanel) {
        // Create info panel if it doesn't exist
        createInfoPanel();
        return displayDistrictInfo(data);
    }
    
    const level = data.neglect_level || 'unknown';
    const levelClass = level === 'low' ? 'success' : level === 'medium' ? 'warning' : 'danger';
    
    infoPanel.innerHTML = `
        <div class="info-header">
            <h3>${data.district}</h3>
            <button onclick="closeInfoPanel()" class="close-btn">&times;</button>
        </div>
        <div class="info-content">
            <div class="metric-row">
                <span class="metric-label">Neglect Score:</span>
                <span class="metric-value ${levelClass}">${data.neglect_score}</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Level:</span>
                <span class="metric-value ${levelClass}">${level.toUpperCase()}</span>
            </div>
            <hr>
            <div class="metric-row">
                <span class="metric-label">Grievances:</span>
                <span class="metric-value">${data.grievance_count}</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Disasters:</span>
                <span class="metric-value">${data.disaster_count}</span>
            </div>
            <div class="metric-row">
                <span class="metric-label">Policy Score:</span>
                <span class="metric-value">${data.policy_score}</span>
            </div>
            <hr>
            <small class="update-time">Last updated: ${new Date(data.last_updated).toLocaleString()}</small>
        </div>
        <div class="info-actions">
            <button onclick="viewDistrictDetails('${data.district}')" class="btn-primary">View Details</button>
        </div>
    `;
    
    infoPanel.style.display = 'block';
}

/**
 * Create info panel element dynamically
 */
function createInfoPanel() {
    const panel = document.createElement('div');
    panel.id = 'district-info';
    panel.className = 'district-info-panel';
    document.body.appendChild(panel);
}

/**
 * Close info panel
 */
function closeInfoPanel() {
    const infoPanel = document.getElementById('district-info');
    if (infoPanel) {
        infoPanel.style.display = 'none';
    }
    
    // Remove district highlight
    document.querySelectorAll('.district, #Pokhara').forEach(d => {
        d.style.strokeWidth = '1';
        d.style.stroke = '#333';
    });
    
    currentDistrict = null;
}

/**
 * View detailed information about a district (navigate to details page)
 * @param {string} districtName - District name
 */
function viewDistrictDetails(districtName) {
    // This would navigate to a detailed district page
    // For now, we'll show an alert
    alert(`Detailed view for ${districtName} - This would navigate to a detailed page`);
}

/**
 * Show error message
 * @param {string} message - Error message to display
 */
function showError(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);
    
    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

/**
 * Refresh map data
 */
function refreshMapData() {
    districtData = {};
    loadAllDistrictData();
    if (currentDistrict) {
        fetchDistrictDetails(currentDistrict);
    }
}

// Expose functions to global scope for HTML onclick attributes
window.closeInfoPanel = closeInfoPanel;
window.viewDistrictDetails = viewDistrictDetails;
window.refreshMapData = refreshMapData;
