# LMS API Documentation

## Table of Contents
- [Authentication](#authentication)
- [Rate Limiting](#rate-limiting)
- [Question Options API](#question-options-api)
- [Lessons API](#lessons-api)
- [Exams API](#exams-api)
- [Exam Results API](#exam-results-api)
- [Error Handling](#error-handling)

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the authentication token in the request header:
```bash
Authorization: Bearer <your-token>
```

## Rate Limiting
The API is rate-limited to 60 requests per minute per user.

## Question Options API
Base URL: `/api/question-options`

### Basic CRUD Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List all question options |
| POST | `/` | Create new question option |
| GET | `/{option}` | Get specific question option |
| PUT | `/{option}` | Update question option |
| DELETE | `/{option}` | Delete question option |

### Additional Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/question/{questionId}/all` | Get all options for a specific question |
| GET | `/question/{questionId}/correct` | Get correct options for a question |
| GET | `/correct/all` | Get all correct options across questions |

### Request/Response Examples

#### Create Question Option
```json
// POST /api/question-options
// Request
{
    "question_id": 1,
    "option_text": "Paris",
    "is_correct": true
}

// Response
{
    "status": 201,
    "data": {
        "id": 1,
        "question_id": 1,
        "option_text": "Paris",
        "is_correct": true,
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    }
}
```

## Lessons API
Base URL: `/api/lessons`

### Basic CRUD Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List all lessons |
| POST | `/` | Create new lesson |
| GET | `/{lesson}` | Get specific lesson |
| PUT | `/{lesson}` | Update lesson |
| DELETE | `/{lesson}` | Delete lesson |

### Additional Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/course/{courseId}/all` | Get all lessons for a course |
| GET | `/{lesson}/next` | Get next lesson in sequence |
| GET | `/{lesson}/previous` | Get previous lesson in sequence |
| GET | `/latest/all` | Get 5 most recent lessons |

### Request/Response Examples

#### Create Lesson
```json
// POST /api/lessons
// Request
{
    "course_id": 1,
    "title": "Introduction to Laravel",
    "content": "Laravel is a web application framework..."
}

// Response
{
    "status": 201,
    "data": {
        "id": 1,
        "course_id": 1,
        "title": "Introduction to Laravel",
        "content": "Laravel is a web application framework...",
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    }
}
```

## Exams API
Base URL: `/api/exams`

### Basic CRUD Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List all exams |
| POST | `/` | Create new exam |
| GET | `/{exam}` | Get specific exam |
| PUT | `/{exam}` | Update exam |
| DELETE | `/{exam}` | Delete exam |

### Additional Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/course/{courseId}/all` | Get all exams for a course |
| GET | `/upcoming/all` | Get all upcoming exams |
| GET | `/past/all` | Get all past exams |
| GET | `/today/all` | Get today's exams |
| POST | `/date-range` | Get exams within date range |

### Request/Response Examples

#### Create Exam
```json
// POST /api/exams
// Request
{
    "course_id": 1,
    "title": "Final Exam",
    "exam_date": "2024-04-15T09:00:00Z"
}

// Response
{
    "status": 201,
    "data": {
        "id": 1,
        "course_id": 1,
        "title": "Final Exam",
        "exam_date": "2024-04-15T09:00:00Z",
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    }
}
```

#### Get Exams by Date Range
```json
// POST /api/exams/date-range
// Request
{
    "start_date": "2024-03-20",
    "end_date": "2024-03-27"
}

// Response
{
    "status": 200,
    "data": [
        {
            "id": 1,
            "title": "Final Exam",
            "exam_date": "2024-03-25T09:00:00Z",
            // ... other exam details
        }
    ]
}
```

## Exam Results API
Base URL: `/api/exam-results`

### Basic CRUD Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/` | List all exam results |
| POST | `/` | Create new exam result |
| GET | `/{examResult}` | Get specific exam result |
| PUT | `/{examResult}` | Update exam result |
| DELETE | `/{examResult}` | Delete exam result |

### Student-Specific Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/student/{studentId}/all` | Get all results for a student |
| GET | `/student/{studentId}/performance` | Get student performance summary |
| GET | `/student/{studentId}/recent` | Get student's recent results |

### Exam-Specific Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/exam/{examId}/all` | Get all results for an exam |
| GET | `/exam/{examId}/statistics` | Get exam statistics |
| GET | `/exam/{examId}/top-performers` | Get top performers for exam |

### Request/Response Examples

#### Create Exam Result
```json
// POST /api/exam-results
// Request
{
    "student_id": 1,
    "exam_id": 1,
    "score": 85,
    "total_marks": 100
}

// Response
{
    "status": 201,
    "data": {
        "id": 1,
        "student_id": 1,
        "exam_id": 1,
        "score": 85,
        "total_marks": 100,
        "created_at": "2024-03-20T12:00:00Z",
        "updated_at": "2024-03-20T12:00:00Z"
    }
}
```

#### Get Student Performance Summary
```json
// GET /api/exam-results/student/1/performance
// Response
{
    "status": 200,
    "data": {
        "total_exams": 10,
        "average_score": 85.5,
        "highest_score": 98,
        "lowest_score": 65,
        "passed_exams": 9,
        "failed_exams": 1
    }
}
```

#### Get Exam Statistics
```json
// GET /api/exam-results/exam/1/statistics
// Response
{
    "status": 200,
    "data": {
        "total_students": 30,
        "average_score": 78.5,
        "highest_score": 100,
        "lowest_score": 45,
        "pass_count": 25,
        "fail_count": 5
    }
}
```

## Error Handling

### Error Response Format
All endpoints follow a standard error response format:
```json
{
    "status": 4xx,
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### HTTP Status Codes
- 200: Success
- 201: Created
- 204: No Content
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

## Versioning
Current API Version: v1

## Support
For any API related queries, please contact the development team.
