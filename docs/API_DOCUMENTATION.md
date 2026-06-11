# School Management System API Documentation

## Base URL

```http
http://{tenant-domain}/api
```

---

## Authentication

### Register

### Login

----------------------------------------------------------------------

# Grades

**Authorization Required**

```http
Authorization: Bearer {token}
```

**Allowed Role**

```text
Admin
Supervisor
```

---

## List Grades

Retrieve all grades with their assigned supervisors.

### Endpoint

```http
GET /grades
```

### Success Response

```json
{
    "success": true,
    "message": "Grades fetched successfully",
    "data": [
        {
            "id": 1,
            "name": "Grade 10",
            "supervisor": {
                "id": 2,
                "name": "John Supervisor"
            }
        }
    ]
}
```

### Status Codes

| Code | Description                 |
| ---- | --------------------------- |
| 200  | Grades fetched successfully |
| 401  | Unauthenticated             |
| 403  | Forbidden                   |

---

## Show Grade

Retrieve a specific grade.

### Endpoint

```http
GET /grades/{grade}
```

### Example

```http
GET /grades/1
```

### Success Response

```json
{
    "success": true,
    "message": "Grade fetched successfully",
    "data": {
        "id": 1,
        "name": "Grade 10",
        "supervisor": {
            "id": 2,
            "name": "John Supervisor"
        }
    }
}
```

### Status Codes

| Code | Description                |
| ---- | -------------------------- |
| 200  | Grade fetched successfully |
| 404  | Grade not found            |
| 401  | Unauthenticated            |
| 403  | Forbidden                  |

---

## Create Grade

Create a new grade.

### Endpoint

```http
POST /grades
```

### Request Body

```json
{
    "name": "Grade 10"
}
```

### Validation Rules

| Field | Rules                    |
| ----- | ------------------------ |
| name  | required, string, unique |

### Success Response

```json
{
    "success": true,
    "message": "Grade created successfully",
    "data": {
        "id": 1,
        "name": "Grade 10",
        "supervisor": {
            "id": 2,
            "name": "John Supervisor"
        }
    }
}
```

### Validation Error Example

```json
{
    "message": "The name has already been taken.",
    "errors": {
        "name": [
            "The name has already been taken."
        ]
    }
}
```

### Status Codes

| Code | Description                |
| ---- | -------------------------- |
| 201  | Grade created successfully |
| 422  | Validation failed          |
| 401  | Unauthenticated            |
| 403  | Forbidden                  |

---

## Update Grade

Update an existing grade.

### Endpoint

```http
PUT /grades/{grade}
```

### Example

```http
PUT /grades/1
```

### Request Body

```json
{
    "name": "Grade 11"
}
```

### Validation Rules

| Field | Rules                                           |
| ----- | ----------------------------------------------- |
| name  | required, string, unique (except current grade) |

### Success Response

```json
{
    "success": true,
    "message": "Grade updated successfully",
    "data": {
        "id": 1,
        "name": "Grade 11",
        "supervisor": {
            "id": 2,
            "name": "John Supervisor"
        }
    }
}
```

### Status Codes

| Code | Description                |
| ---- | -------------------------- |
| 200  | Grade updated successfully |
| 422  | Validation failed          |
| 404  | Grade not found            |
| 401  | Unauthenticated            |
| 403  | Forbidden                  |

---

## Delete Grade

Delete a grade.

### Endpoint

```http
DELETE /grades/{grade}
```

### Example

```http
DELETE /grades/1
```

### Success Response

```json
{
    "success": true,
    "message": "Grade deleted successfully",
    "data": null
}
```

### Status Codes

| Code | Description                |
| ---- | -------------------------- |
| 200  | Grade deleted successfully |
| 404  | Grade not found            |
| 401  | Unauthenticated            |
| 403  | Forbidden                  |

```
```


----------------------------------------------------------------------

# Sections

**Authorization Required**

```http
Authorization: Bearer {token}
```

**Allowed Role**

```
Admin
Supervisor
```

---

## List Sections

Retrieve all sections with their associated grades.

### Endpoint

```http
GET /sections
```

### Success Response

```json
{
    "success": true,
    "message": "Sections fetched successfully",
    "data": [
        {
            "id": 1,
            "name": "A",
            "grade": {
                "id": 1,
                "name": "Grade 10"
            }
        }
    ]
}
```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 200  | Sections fetched successfully |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

---

## Show Section

Retrieve a specific section.

### Endpoint

```http
GET /sections/{section}
```

### Example

```http
GET /sections/1
```

### Success Response

```json
{
    "success": true,
    "message": "Section fetched successfully",
    "data": {
        "id": 1,
        "name": "A",
        "grade": {
            "id": 1,
            "name": "Grade 10"
        }
    }
}
```

### Status Codes

| Code | Description                  |
| ---- | ---------------------------- |
| 200  | Section fetched successfully |
| 404  | Section not found            |
| 401  | Unauthenticated              |
| 403  | Forbidden                    |

---

## Create Section

Create a new section.

### Endpoint

```http
POST /sections
```

### Request Body

```json
{
    "name": "A",
    "grade_id": 1
}
```

### Validation Rules

| Field    | Rules                      |
| -------- | -------------------------- |
| name     | required, string, max:255  |
| grade_id | required, exists:grades,id |

### Success Response

```json
{
    "success": true,
    "message": "Section created successfully",
    "data": {
        "id": 1,
        "name": "A",
        "grade": {
            "id": 1,
            "name": "Grade 10"
        }
    }
}
```

### Forbidden Response

```json
{
    "success": false,
    "message": "You are not allowed to create a section for this grade."
}
```

### Status Codes

| Code | Description                       |
| ---- | --------------------------------- |
| 201  | Section created successfully      |
| 403  | Supervisor does not own the grade |
| 422  | Validation failed                 |
| 401  | Unauthenticated                   |

---

## Update Section

Update an existing section.

### Endpoint

```http
PUT /sections/{section}
```

### Example

```http
PUT /sections/1
```

### Request Body

```json
{
    "name": "B",
    "grade_id": 2
}
```

### Validation Rules

| Field    | Rules                       |
| -------- | --------------------------- |
| name     | sometimes, string, max:255  |
| grade_id | sometimes, exists:grades,id |

### Success Response

```json
{
    "success": true,
    "message": "Section updated successfully",
    "data": {
        "id": 1,
        "name": "B",
        "grade": {
            "id": 2,
            "name": "Grade 11"
        }
    }
}
```

### Forbidden Response

```json
{
    "success": false,
    "message": "You are not allowed to move this section to that grade."
}
```

### Status Codes

| Code | Description                          |
| ---- | ------------------------------------ |
| 200  | Section updated successfully         |
| 403  | Supervisor does not own target grade |
| 422  | Validation failed                    |
| 404  | Section not found                    |
| 401  | Unauthenticated                      |

---

## Delete Section

Delete a section.

### Endpoint

```http
DELETE /sections/{section}
```

### Example

```http
DELETE /sections/1
```

### Success Response

```json
{
    "success": true,
    "message": "Section deleted successfully",
    "data": null
}
```

### Status Codes

| Code | Description                  |
| ---- | ---------------------------- |
| 200  | Section deleted successfully |
| 404  | Section not found            |
| 401  | Unauthenticated              |
| 403  | Forbidden                    |

```
```

----------------------------------------------------------------------

# Enrollments

**Authorization Required**

```http
Authorization: Bearer {token}
```

**Allowed Roles**

```text
Admin
Supervisor
```

---

## Create Enrollment

Enroll a student into a grade, section, and academic year.

### Endpoint

```http
POST /enrollments
```

### Request Body

```json
{
    "student_id": 5,
    "grade_id": 1,
    "section_id": 2,
    "academic_year_id": 1,
    "enrollment_date": "2025-09-01"
}
```

### Validation Rules

| Field            | Rules                              |
| ---------------- | ---------------------------------- |
| student_id       | required, exists:users,id          |
| grade_id         | required, exists:grades,id         |
| section_id       | required, exists:sections,id       |
| academic_year_id | required, exists:academic_years,id |
| enrollment_date  | required, date                     |

### Success Response

```json
{
    "success": true,
    "message": "Student enrolled successfully",
    "data": {
        "id": 1,
        "student_id": 5,
        "grade_id": 1,
        "section_id": 2,
        "academic_year_id": 1,
        "enrollment_date": "2025-09-01"
    }
}
```

### Validation Error Example

```json

{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "student_id": [
            "The selected student id is invalid."
        ]
    }
}

```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 201  | Student enrolled successfully |
| 422  | Validation failed             |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

```
```


------------------------------------------------------------------------

# Teacher Subjects

**Authorization Required**

```http
Authorization: Bearer {token}
```

**Allowed Roles**

```text
Admin
Supervisor
```

---

## Assign Teacher To Subject

Assign a teacher to a subject within a section and academic year.

### Endpoint

```http
POST /teacher-subjects
```

### Request Body

```json
{
    "teacher_id": 3,
    "subject_id": 2,
    "section_id": 1,
    "academic_year_id": 1
}
```

### Validation Rules

| Field            | Rules                              |
| ---------------- | ---------------------------------- |
| teacher_id       | required, exists:users,id          |
| subject_id       | required, exists:subjects,id       |
| section_id       | required, exists:sections,id       |
| academic_year_id | required, exists:academic_years,id |

### Success Response

```json
{
    "success": true,
    "message": "Teacher assigned successfully",
    "data": {
        "id": 1,
        "teacher_id": 3,
        "subject_id": 2,
        "section_id": 1,
        "academic_year_id": 1,
        "created_at": "2025-08-01T10:00:00.000000Z",
        "updated_at": "2025-08-01T10:00:00.000000Z"
    }
}
```

### Validation Error Example

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "teacher_id": [
            "The selected teacher id is invalid."
        ]
    }
}
```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 201  | Teacher assigned successfully |
| 422  | Validation failed             |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

```
```


--------------------------------------------------------------------------

# Marks

**Authorization Required**

```http
Authorization: Bearer {token}
```

---

## Create Mark

**Allowed Role**

```text
Teacher
```

### Endpoint

```http
POST /marks
```

### Request Body

```json
{
    "enrollment_id": 1,
    "teacher_subject_id": 2,
    "score": 80,
    "max_score": 100,
    "term": 1,
    "type": "midterm",
    "exam_date": "2025-08-01"
}
```

### Validation Rules

| Field              | Rules                                   |
| ------------------ | --------------------------------------- |
| enrollment_id      | required, exists:student_enrollments,id |
| teacher_subject_id | required, exists:teacher_subjects,id    |
| score              | required, numeric, min:0                |
| max_score          | required, numeric, gt:score             |
| term               | required, integer, between:1,2          |
| type               | required, midterm/final                 |
| exam_date          | required, date                          |

### Success Response

```json
{
    "success": true,
    "message": "Mark added successfully",
    "data": {
        "id": 1,
        "score": 80,
        "max_score": 100,
        "term": 1,
        "type": "midterm",
        "exam_date": "2025-08-01",
        "subject": {
            "id": 2,
            "name": "Mathematics"
        },
        "teacher": {
            "id": 3,
            "name": "Teacher Name"
        },
        "student": {
            "id": 5,
            "name": "Student Name"
        }
    }
}
```

---

## Show Mark

### Endpoint

```http
GET /marks/{mark}
```

### Allowed Roles

```text
Admin
Teacher (own marks only)
Student (own marks only)
```

### Success Response

```json
{
    "success": true,
    "message": "Mark fetched successfully",
    "data": {
        ...
    }
}
```

---

## Update Mark

### Endpoint

```http
PUT /marks/{mark}
```

### Allowed Role

```text
Teacher (owner only)
```

### Request Body

```json
{
    "score": 90,
    "max_score": 100,
    "term": 1,
    "type": "final",
    "exam_date": "2025-08-15"
}
```

### Validation Rules

| Field     | Rules                     |
| --------- | ------------------------- |
| score     | sometimes, numeric, min:0 |
| max_score | sometimes, numeric, min:1 |
| term      | sometimes, integer        |
| type      | sometimes, midterm/final  |
| exam_date | sometimes, date           |

### Success Response

```json
{
    "success": true,
    "message": "Mark updated successfully",
    "data": {
        ...
    }
}
```

---

## Delete Mark

### Endpoint

```http
DELETE /marks/{mark}
```

### Allowed Role

```text
Admin
```

### Success Response

```json
{
    "success": true,
    "message": "Mark deleted successfully",
    "data": null
}
```

---

## Teacher Marks

Retrieve all marks assigned by the authenticated teacher.

### Endpoint

```http
GET /teacher-marks
```

### Allowed Role

```text
Teacher
```

### Query Parameters

```http
?per_page=15
```

### Success Response

```json
{
    "success": true,
    "message": "Marks fetched successfully",
    "data": [...]
}
```

---

## Student Marks

Retrieve all marks belonging to the authenticated student.

### Endpoint

```http
GET /student-marks
```

### Allowed Role

```text
Student
```

### Query Parameters

```http
?per_page=15
```

### Success Response

```json
{
    "success": true,
    "message": "Marks fetched successfully",
    "data": [...]
}
```

### Student Not Enrolled

```json
{
    "success": false,
    "message": "Student is not enrolled",
    "errors": null
}
```


---------------------------------------------------------------------------------

# Attendance

**Authorization Required**

```http
Authorization: Bearer {token}
```

---

## Create Attendance

### Allowed Role

```text
Supervisor
```

### Endpoint

```http
POST /attendances
```

### Request Body

```json
{
    "enrollment_id": 1,
    "date": "2025-08-01",
    "status": "present"
}
```

### Validation Rules

| Field         | Rules                                   |
| ------------- | --------------------------------------- |
| enrollment_id | required, exists:student_enrollments,id |
| date          | required, date                          |
| status        | required, present/absent/late           |

### Success Response

```json
{
    "success": true,
    "message": "Attendance recorded successfully",
    "data": {
        "id": 1,
        "date": "2025-08-01",
        "status": "present",
        "student": {
            "id": 5,
            "name": "Student Name"
        },
        "section": {
            "id": 2,
            "name": "Section A"
        }
    }
}
```

---

## Update Attendance

### Allowed Role

```text
Supervisor
```

### Endpoint

```http
PUT /attendances/{attendance}
```

### Request Body

```json
{
    "status": "absent"
}
```

### Success Response

```json
{
    "success": true,
    "message": "Attendance updated successfully",
    "data": {
        "id": 1,
        "date": "2025-08-01",
        "status": "absent"
    }
}
```

---

## Delete Attendance

### Allowed Role

```text
Supervisor
```

### Endpoint

```http
DELETE /attendances/{attendance}
```

### Success Response

```json
{
    "success": true,
    "message": "Attendance deleted successfully",
    "data": null
}
```

---

## Student Attendances

Retrieve attendance records of the authenticated student.

### Allowed Role

```text
Student
```

### Endpoint

```http
GET /student-attendances
```

### Query Parameters

```http
?per_page=15
```

### Success Response

```json
{
    "success": true,
    "message": "Attendances fetched successfully",
    "data": [...]
}
```

### Student Not Enrolled

```json
{
    "success": false,
    "message": "Student is not enrolled",
    "errors": null
}
```

---

## Supervisor Attendances

Retrieve attendance records for students belonging to grades supervised by the authenticated supervisor.

### Allowed Role

```text
Supervisor
```

### Endpoint

```http
GET /supervisor-attendances
```

### Query Parameters

```http
?per_page=15
```

### Success Response

```json
{
    "success": true,
    "message": "Attendances fetched successfully",
    "data": [...]
}
```


-------------------------------------------------------------------------------------

# Schedule

**Authorization Required**

```http
Authorization: Bearer {token}
```

---

## Create Schedule

Create a new class schedule.

### Allowed Roles

```text
Admin
Supervisor
```

### Endpoint

```http
POST /schedules
```

### Request Body

```json
{
    "teacher_subject_id": 1,
    "day": "monday",
    "period": 1,
    "start_time": "08:00",
    "end_time": "09:00"
}
```

### Validation Rules

| Field              | Rules                                              |
| ------------------ | -------------------------------------------------- |
| teacher_subject_id | required, exists:teacher_subjects,id               |
| day                | required, sunday/monday/tuesday/wednesday/thursday |
| period             | required, integer, min:1                           |
| start_time         | required, date_format:H:i                          |
| end_time           | required, date_format:H:i, after:start_time        |

### Business Rules

* Supervisor can only manage schedules belonging to grades assigned to them.
* A teacher cannot have two classes at the same day and period.
* A section cannot have two classes at the same day and period.

### Success Response

```json
{
    "success": true,
    "message": "Schedule created successfully",
    "data": {
        "id": 1,
        "teacher_subject_id": 1,
        "day": "monday",
        "period": 1,
        "start_time": "08:00",
        "end_time": "09:00"
    }
}
```

### Teacher Conflict Example

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "teacher_subject_id": [
            "Teacher already has a class in this period."
        ]
    }
}
```

### Section Conflict Example

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "period": [
            "Section already has a class in this period."
        ]
    }
}
```

### Unauthorized Grade Example

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "teacher_subject_id": [
            "You cannot manage schedules outside your assigned grades."
        ]
    }
}
```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 201  | Schedule created successfully |
| 422  | Validation failed             |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

---

## Update Schedule

Update an existing schedule.

### Allowed Roles

```text
Admin
Supervisor (assigned grades only)
```

### Endpoint

```http
PUT /schedules/{schedule}
```

### Request Body

```json
{
    "teacher_subject_id": 1,
    "day": "monday",
    "period": 2,
    "start_time": "09:00",
    "end_time": "10:00"
}
```

### Success Response

```json
{
    "success": true,
    "message": "Schedule updated successfully",
    "data": {
        "id": 1,
        "teacher_subject_id": 1,
        "day": "monday",
        "period": 2,
        "start_time": "09:00",
        "end_time": "10:00"
    }
}
```

### Conflict Error Example

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "period": [
            "Section already has a class in this period."
        ]
    }
}
```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 200  | Schedule updated successfully |
| 422  | Validation failed             |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

---

## Delete Schedule

Delete a schedule.

### Allowed Roles

```text
Admin
Supervisor (assigned grades only)
```

### Endpoint

```http
DELETE /schedules/{schedule}
```

### Success Response

```json
{
    "success": true,
    "message": "Schedule deleted successfully",
    "data": null
}
```

### Status Codes

| Code | Description                   |
| ---- | ----------------------------- |
| 200  | Schedule deleted successfully |
| 401  | Unauthenticated               |
| 403  | Forbidden                     |

---

## Teacher Schedule

Retrieve the schedule of the authenticated teacher.

### Allowed Role

```text
Teacher
```

### Endpoint

```http
GET /teacher-schedules
```

### Success Response

```json
{
    "success": true,
    "message": "Schedules fetched successfully",
    "data": [
        {
            "id": 1,
            "day": "monday",
            "period": 1,
            "start_time": "08:00",
            "end_time": "09:00"
        }
    ]
}
```

### Notes

Schedules are ordered by:

1. Day
2. Period

---

## Student Schedule

Retrieve the schedule of the authenticated student's section.

### Allowed Role

```text
Student
```

### Endpoint

```http
GET /student-schedules
```

### Success Response

```json
{
    "success": true,
    "message": "Schedules fetched successfully",
    "data": [
        {
            "id": 1,
            "day": "monday",
            "period": 1,
            "start_time": "08:00",
            "end_time": "09:00"
        }
    ]
}
```

### Student Not Enrolled

```json
{
    "success": false,
    "message": "Student is not enrolled",
    "errors": null
}
```

---

## Supervisor Schedule

Retrieve schedules belonging to grades supervised by the authenticated supervisor.

### Allowed Role

```text
Supervisor
```

### Endpoint

```http
GET /supervisor-schedules
```

### Success Response

```json
{
    "success": true,
    "message": "Schedules fetched successfully",
    "data": [
        {
            "id": 1,
            "day": "monday",
            "period": 1,
            "start_time": "08:00",
            "end_time": "09:00"
        }
    ]
}
```


----------------------------------------------------------------------------------------

## Standard Success Response

```json
{
    "success": true,
    "message": "Operation completed successfully",
    "data": {}
}
```

## Standard Error Response

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {}
}
```
