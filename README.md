# Course REST API

A RESTful CRUD API for managing courses with pagination, validation, and soft deletes built with Laravel.

## Features

- Complete CRUD operations for courses
- Pagination support
- Input validation with custom error messages
- Soft delete functionality
- Comprehensive test coverage (Unit & Feature tests)
- OpenAPI documentation
- Clean architecture with Repository pattern

## Requirements

- Docker and Docker Compose
- Git

## Get started

```
# 1. Clone your repo
git clone https://github.com/TeoMastro/course_api.git
cd course_api

# 2. Install dependencies
docker run --rm -v $(pwd):/opt -w /opt laravelsail/php84-composer:latest composer install

# 3. Set permissions
sudo chown -R $USER: .

# 4. Create environment file
cp .env.example .env

# 5. Edit .env file if needed for your local setup (set different ports, etc.)

# 6. Start Sail
./vendor/bin/sail up -d

# 7. Generate app key
./vendor/bin/sail artisan key:generate

# 8. Clear configuration cache (to re-read the .env in case another one was cached by laravel)
./vendor/bin/sail artisan config:clear

# 9. Run migrations
./vendor/bin/sail artisan migrate

# 10. You are ready. Feel free to open localhost and see the app running.

```

## API Endpoints

The API will be available at `http://localhost:80/api/courses`

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/courses` | Get paginated list of courses |
| GET | `/api/courses/{id}` | Get specific course by ID |
| POST | `/api/courses` | Create a new course |
| PUT | `/api/courses/{id}` | Update existing course |
| DELETE | `/api/courses/{id}` | Soft delete course |

## Running Tests

### Run all tests

```
./vendor/bin/sail artisan test
```

### Run specific test types

```
./vendor/bin/sail artisan test tests/Feature
```

## API Documentation

### View Interactive Documentation

1. **Copy the API specification located in root project folder at api-docs.yaml**

2. **Open Swagger Editor:**
- Go to [https://editor.swagger.io](https://editor.swagger.io)
- Paste the content from `api-docs.yaml`
- The interactive documentation will render automatically

## To run in development mode:

```
./vendor/bin/sail up -d 
```
