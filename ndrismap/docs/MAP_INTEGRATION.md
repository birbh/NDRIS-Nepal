# Interactive Map Integration

## Reference
Based on INTmap repository:
https://github.com/birbh/INTmap.git

## Map Type
- SVG-based Nepal map
- Each district represented as a path element

## Interaction Logic
- User clicks district
- JavaScript captures district ID
- AJAX request sent to PHP backend
- Backend fetches relevant data
- Popup or side panel displays information

## Data Visualized
- Disaster count
- Grievance volume
- Policy score
- Neglect Index

## Color Encoding
- Green: Low neglect
- Yellow: Medium neglect
- Red: High neglect

## Purpose
Transforms abstract data into intuitive geographic insight.