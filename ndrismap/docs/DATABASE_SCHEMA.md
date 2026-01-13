# Database Schema

## disasters
Stores historical disaster records.

Fields:
- id (INT, PK, AUTO_INCREMENT)
- title (VARCHAR)
- type (VARCHAR)
- district (VARCHAR)
- year (INT)
- impact_level (INT)
- description (TEXT)
- created_at (TIMESTAMP)

## grievances
Stores citizen-submitted issues.

Fields:
- id (INT, PK, AUTO_INCREMENT)
- category (VARCHAR)
- district (VARCHAR)
- description (TEXT)
- status (VARCHAR, default: pending)
- created_at (TIMESTAMP)

## policies
Tracks policies and their effectiveness.

Fields:
- id (INT, PK, AUTO_INCREMENT)
- policy_name (VARCHAR)
- sector (VARCHAR)
- district (VARCHAR)
- effectiveness_score (INT)
- notes (TEXT)

## neglect_index
Stores computed neglect scores.

Fields:
- district (VARCHAR, PK)
- grievance_count (INT)
- disaster_count (INT)
- policy_score (INT)
- neglect_score (FLOAT)