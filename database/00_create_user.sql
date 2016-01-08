-- create admin user --
CREATE USER 'offical_admin'@'localhost' IDENTIFIED BY 'abcd1234';

-- GRANT permission for admin user --
GRANT ALL PRIVILEGES ON `official`. * TO 'offical_admin'@'localhost';
FLUSH PRIVILEGES;
