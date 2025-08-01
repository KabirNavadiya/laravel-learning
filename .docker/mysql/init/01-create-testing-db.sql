-- Create testing database
CREATE DATABASE IF NOT EXISTS `laravel_learning_testing`;

-- Grant privileges to our user for testing database
GRANT ALL PRIVILEGES ON `laravel_learning_testing`.* TO 'laraveluser'@'%';
FLUSH PRIVILEGES;