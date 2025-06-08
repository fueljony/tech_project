# Development Plan for Survey Generator App

## Technical Notes
- Using Docker container (port 3001)
- All commands via `./vendor/bin/sail`
- vue is vue2
- Design system: Vuetify
- State management: Pinia
- Database: SQLite
- Run migrations between adding models
- Follow design screenshots in design_screenshots/

## Progress Tracking
After completing each task/step, I will provide:
1. **Completion Summary**: A brief description of what was implemented
2. **Verification Steps**: Specific actions to test the implementation
3. **Potential Gaps**: Any incomplete items or edge cases to consider
4. **should this be a commit**: Looking forward, does it make sense to commit now or will it be better to include more steps.

Example:
```
Task: Implement survey creation
Completion Summary: Added POST /api/surveys endpoint with validation
Verification Steps:
- Create a new survey via API
- Verify validation rules
- Check database entry
Potential Gaps:
- Need to handle duplicate names
- Consider adding description field
```

## Project Setup Status
- [x] Laravel project initialized
- [x] Docker container configured (port 3001)
- [x] Vuetify design system integrated
- [x] Pinia store configured
- [x] Basic routing structure
- [x] Database migrations (partially complete)

## Existing Database Structure
### Surveys Table
- id
- name
- timestamps
- soft deletes

### Survey Questions Table
- id
- survey_id (foreign key)
- question
- value_type (multiple_choice, date_picker, input_text, multiple_answer)
- options (JSON)
- timestamps
- soft deletes

### Survey Answers Table
- id
- survey_question_id (foreign key)
- string_value
- date_value
- json_value
- timestamps
- soft deletes

## Development Sequence

### 1. Backend Foundation
- [x] Review and update existing migrations
  - [x] Add any missing fields to surveys table
  - [x] Add validation rules to questions table
  - [x] Add required flag to questions table
  - [x] Add order field to questions table

- [x] Complete Survey Controller
  - [x] Review existing index method
  - [x] Implement show method
  - [x] Implement store method
  - [x] Implement update method
  - [x] Implement destroy method
  - [x] Add validation rules
  - [x] Add Answer submission method

- [x] Complete Question Controller
  - [x] Review existing addQuestion method
  - [x] Review existing updateQuestion method
  - [x] Implement deleteQuestion method
  - [x] Add validation rules

- [ ] Create Answer Controller
  - [ ] Implement store method
  - [ ] Implement index method (for admin)
  - [ ] Implement show method
  - [ ] Add validation rules

### 2. API Resources
- [x] Create Survey Resource
  - [x] Basic survey data

- [x] Create Question Resource
  - [x] Question data
  - [x] Validation rules
  - [x] Options for multiple choice
  - [x] Order information

- [ ] Create Answer Resource
  - [x] Answer Submission
  - [ ] Answer data
  - [ ] Question reference

### 3. Frontend Foundation
- [x] Survey List View
  - [x] Create survey store module
    - [x] Survey list state
    - [x] Loading states
    - [x] Error handling
  - [x] Implement API integration
    - [x] Fetch survey list
    - [x] Create survey
    - [x] Delete survey
  - [x] Build components
    - [x] Survey list container
    - [x] Survey card (replaced by v-data-table row)
    - [x] Create survey form
    - [x] Delete confirmation

- [x] Survey Editor View
  - [x] Add question to survey store
    - [x] Question list state
    - [x] Editing state
    - [x] Validation state
  - [x] Implement API integration
    - [x] Fetch survey details
    - [x] Fetch questions
    - [x] Add question
    - [x] Update question
    - [x] Delete question
    - [ ] Reorder questions
  - [x] Build components
    - [x] Question list
    - [x] Question editor
    - [x] Question type selector
    - [x] Question preview
    - [x] Add question modal

- [ ] Survey Respondent View
  - [ ] Create response store module
    - [ ] Response state
    - [ ] Validation state
    - [ ] Submission state
  - [x] Implement API integration
    - [x] Fetch survey
    - [x] Submit response
  - [ ] Build components
    - [ ] Question display
    - [ ] Input components
    - [ ] Progress indicator
    - [ ] Submit button
    - [ ] Success message

### 4. Testing
- [ ] Survey Tests
  - [ ] CRUD operations
  - [ ] Validation rules
  - [ ] Error handling

- [ ] Question Tests
  - [ ] CRUD operations
  - [ ] Validation rules
  - [ ] Reordering
  - [ ] Error handling

- [ ] Answer Tests
  - [ ] Submission
  - [ ] Validation
  - [ ] Retrieval
  - [ ] Error handling
