# Modules & CRUD Mapping

## Disaster Memory Module
File: php/disaster_crud.php

- Create: Add new disaster records
- Read: View disaster history by district/year
- Update: Correct or enhance records
- Delete: Remove invalid data

## Citizen Grievance Module
File: php/grievance_crud.php

- Create: Citizen submits grievance
- Read: Admin views grievances
- Update: Admin updates status
- Delete: Remove spam/test data

## Policy Impact Module
File: php/policy_crud.php

- Create: Add policy record
- Read: View policy coverage
- Update: Adjust effectiveness score
- Delete: Remove outdated policies

## Urban Neglect Index Module
File: php/neglect_index.php

- Read: Fetch neglect score by district
- Compute: Combine grievances, disasters, policies

## CRUD Rules
- Use MySQLi prepared statements
- No inline SQL in HTML
- Separate logic per module