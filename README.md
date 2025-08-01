# laravel-learning
This repository is STRICTLY for learning purpose 


# ⚠️ Docker Setup Notice

> **Important:**
> 
> **Local development Docker setup** (in the `.docker` directory):
>    - **Purpose:** For all local development work.
>    - **Always use** the `.docker` directory and its `docker-compose.yml` for running the app locally.

## Getting Started with Docker

### Prerequisites
- Ensure Docker and Docker Compose are installed on your system.

### Setup Instructions
1. Clone the repository:
   ```bash
   git clone <repository-url>
   cd into project directory
   ```

2. Add virtual host entry to your hosts file:
   ```bash
   # On Linux/macOS: Edit /etc/hosts
   # On Windows: Edit C:\Windows\System32\drivers\etc\hosts
   
   # Add the following line:
   127.0.0.1   laravel-learning-api.local
   ```

3. Build and start the Docker containers:
   ```bash
   # Build with your host user's UID/GID to prevent permission issues
   USER_ID=$(id -u) GROUP_ID=$(id -g) docker compose -f .docker/docker-compose.yml up -d --build
   ```
> **Note:** The following steps (4, 5, and 6) are now **automated** when starting the containers for the first time. You only need to run them manually if there are issues with the automatic setup.

4. Install dependencies (if not automatically installed):
   ```bash
   docker exec -it learning-laravel-php composer install
   ```

5. Create environment file and generate application key (if not automatically done):
   ```bash
   docker exec -it learning-laravel-php cp .env.example .env
   docker exec -it learning-laravel-php php artisan key:generate
   ```

6. Run database migrations (if not automatically run):
   ```bash
   docker exec -it learning-laravel-php php artisan migrate
   ```

7. Access the application:
   - API Documentation: [http://laravel-learning-api.local/api/documentation](http://laravel-learning-api.local/api/documentation)

### Non-Root User Configuration

The Docker setup is configured to run with a non-root user that matches your host system's user ID and group ID. This prevents permission issues when Docker creates files in mounted volumes.

- The Dockerfile creates a user called `appuser` with your host system's UID/GID
- Commands in the container run as this non-root user
- Files created by the container will have the same ownership as your host user
- If you experience permission issues, rebuild the containers with your specific UID/GID:
  ```bash
  USER_ID=$(id -u) GROUP_ID=$(id -g) docker compose -f .docker/docker-compose.yml up -d --build

### Automated Setup Process

The Docker setup now includes an intelligent entrypoint script that automatically handles:
- Copying `.env.example` to `.env` if it doesn't exist
- Generating the application key
- Installing PHP dependencies
- Waiting for the MySQL database to be ready
- Running database migrations
- Setting up Husky git hooks

This provides a zero-configuration experience for new developers.

> **Important:** After the `.env` file is created automatically (or if you create it manually), verify that all configuration values are correct for your environment. Pay special attention to database credentials, Firebase configuration, and API keys. Incorrect values can lead to connection errors or authentication failures when running the application.

### Example Commands
- To generate Swagger documentation:
  ```bash
  docker exec -it learning-laravel-php php artisan l5-swagger:generate
  ```

- To run tests:
  ```bash
  docker exec -it learning-laravel-php php artisan test
  ```

### Running Tests

This project is configured to use a separate MySQL database for testing to ensure test isolation from development data.

- The main development database is `learning-laravel`
- The testing database is `laravel_learning_testing`

#### First-time Testing Database Setup

> **Note:** The following setup steps are typically **automated** by the Docker entrypoint script during initial container setup. You should only need to run these commands manually if the automated setup didn't complete successfully.

When setting up the project for the first time, the testing database should be automatically created. If for some reason it wasn't, you can manually create it with:

```bash
# Create the testing database
docker exec -it learning-laravel-mysql mysql -uroot -p -e "CREATE DATABASE IF NOT EXISTS laravel_learning_testing; GRANT ALL PRIVILEGES ON laravel_learning_testing.* TO 'laraveluser'@'%'; FLUSH PRIVILEGES;"
password -> root

# Run migrations on the testing database
docker exec -it learning-laravel-php php artisan migrate --env=testing
```

#### Running Tests

To run tests:
```bash
docker exec -it learning-laravel-php php artisan test
```

This command will automatically use the `laravel_learning_testing` database as configured in `.env.testing` file.

If you need to refresh the testing database:
```bash
docker exec -it learning-laravel-php php artisan migrate:fresh --env=testing
```

### Code Quality Tools

This project is set up with the following code quality tools:

1. **PHP Pint** - Laravel's opinionated code style fixer based on PHP-CS-Fixer
2. **PHPStan** - Static analysis tool for PHP
3. **Pest** - Elegant PHP testing framework with a focus on simplicity

#### Running Code Quality Checks

You can run all code quality checks at once using the custom Composer script:

```bash
docker exec -it learning-laravel-php composer code-quality-check
```

Or individually:

- Format code (PHP Pint):
  ```bash
  docker exec -it learning-laravel-php composer format
  ```

- Static analysis (PHPStan):
  ```bash
  docker exec -it learning-laravel-php composer analyse
  ```

- Run tests (Pest):
  ```bash
  docker exec -it learning-laravel-php composer test
  ```

- Generate test coverage report:
  ```bash
  docker exec -it learning-laravel-php composer coverage
  ```

  #### Pre-commit Hooks

This project uses Husky to run pre-commit hooks that automatically check code quality before allowing commits. The pre-commit hook runs:

1. PHP Pint to ensure proper code formatting
2. PHPStan to perform static analysis
3. Pest to run tests

When you commit code, these checks will run automatically. If any check fails, the commit will be aborted, allowing you to fix the issues before trying again.

The pre-commit hooks are configured to work both locally and inside the Docker container. The Docker setup includes Node.js and npm necessary for running Husky.

#### Manually Running Pre-commit Checks

To manually run the same checks that the pre-commit hook runs:

```bash
docker exec -it learning-laravel-php npx lint-staged
```

### Running Tests with Coverage

To run tests with coverage report:

```bash
docker exec -it learning-laravel-php composer coverage
```

This will generate a coverage report and ensure coverage meets the minimum threshold of 80%.