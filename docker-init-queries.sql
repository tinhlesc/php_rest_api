CREATE DATABASE IF NOT EXISTS rest_api;
CREATE USER admin IDENTIFIED BY 'root';
GRANT ALL PRIVILEGES ON rest_api.* TO 'root'@'%';
FLUSH PRIVILEGES;
