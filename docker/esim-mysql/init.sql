CREATE USER IF NOT EXISTS 'esim_user'@'%' IDENTIFIED BY 'esim_password';
GRANT ALL PRIVILEGES ON esim_db.* TO 'esim_user'@'%';
FLUSH PRIVILEGES;