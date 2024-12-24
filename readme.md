# WordPress Deployment with LEMP Stack

This guide provides detailed steps to deploy WordPress on a server using the LEMP stack (Linux, Nginx, MySQL, PHP) with staging and live environments.

## 1. Installing LEMP Stack
Install Nginx, MySQL, and PHP:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install nginx mysql-server php-fpm php-mysql -y
```

## 2. Setting Up MySQL
Configure MySQL for WordPress:

Log in to MySQL:
```bash
sudo mysql
```

Run the following commands:
```sql
CREATE DATABASE wordpress;
CREATE USER 'wordpressuser'@'localhost' IDENTIFIED BY 'Password@1122';
GRANT ALL PRIVILEGES ON wordpress.* TO 'wordpressuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## 3. Installing WordPress
Download and extract WordPress:
```bash
wget https://wordpress.org/latest.tar.gz
tar -xzvf latest.tar.gz
sudo mv wordpress /var/www/html/
```

Set up the directory structure:

Copy WordPress files to a second directory named `wp-live`:
```bash
sudo cp -r /var/www/html/wordpress /var/www/html/wp-live
```

Remove unnecessary files from `wp-live`:
```bash
sudo rm -rf /var/www/html/wp-live/.git
sudo rm -rf /var/www/html/wp-live/wp-admin
sudo rm /var/www/html/wp-live/wp-config.php
sudo rm /var/www/html/wp-live/readme.html
sudo rm /var/www/html/wp-live/license.txt
sudo rm /var/www/html/wp-live/xmlrpc.php
```

The `wordpress` directory serves as the staging environment, while `wp-live` hosts the live website.

## 4. Configuring Nginx
Create Nginx configuration files:

### Development/Staging Configuration
Create a file named `wp-admin.conf`:
```nginx
server {
    listen 80;
    server_name dev.bsasg.zapto.org;
    root /var/www/html/wordpress;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Live Configuration
Create a file named `wp-live.conf`:
```nginx
server {
    listen 80;
    server_name bsasg.zapto.org;
    root /var/www/html/wp-live;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

Enable the configurations:
```bash
sudo ln -s /etc/nginx/sites-available/wp-admin.conf /etc/nginx/sites-enabled/
sudo ln -s /etc/nginx/sites-available/wp-live.conf /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

## 5. Setting Up Domain and SSL Certificates
### Point Server IP to Domain
Update the A record of your domain purchased from [No-IP](https://www.noip.com/) to point to your server's IP address.

### Generate SSL Certificate
Install Certbot:
```bash
sudo apt install certbot python3-certbot-nginx -y
```

Generate SSL certificates for your domain:
```bash
sudo certbot --nginx -d bsasg.zapto.org -d www.bsasg.zapto.org
```

Reload Nginx to apply the certificates:
```bash
sudo systemctl reload nginx
```

## 6. CI/CD Pipeline for Automated Deployment
### GitHub Actions Workflow
Create a GitHub Actions workflow file `.github/workflows/deploy.yml` with the following content:
```yaml
name: WordPress Deployment to VPS

on:
  push:
    branches:
      - master  # Trigger on push to the master branch

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v2

      - name: Set up SSH for deployment
        run: |
          mkdir -p ~/.ssh
          echo "${{ secrets.SSH_PRIVATE_KEY }}" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa
          ssh-keyscan -H ${{ secrets.VPS_IP }} >> ~/.ssh/known_hosts
        env:
          VPS_IP: ${{ secrets.VPS_IP }}

      - name: Install Dependencies
        run: |
          sudo apt update
          sudo apt install -y rsync

      - name: Deploy to VPS
        run: |
          rsync -avz --delete --exclude '.git*' --exclude 'wp-admin' --exclude 'wp-config.php' --exclude 'readme.html' --exclude 'license.txt' --exclude 'xmlrpc.php' ./ ${{ secrets.VPS_USER }}@${{ secrets.VPS_IP }}:/var/www/html/wp-live/
        env:
          VPS_IP: ${{ secrets.VPS_IP }}

      - name: Clear cache on VPS (Optional)
        run: |
          ssh -o StrictHostKeyChecking=no ${{ secrets.VPS_USER }}@${{ secrets.VPS_IP }} 'sudo systemctl restart nginx php8.3-fpm'
        env:
          VPS_IP: ${{ secrets.VPS_IP }}
```

### Steps to Configure
Add the following secrets in your GitHub repository:
- `SSH_PRIVATE_KEY`: Your private SSH key for accessing the VPS.
- `VPS_IP`: The IP address of your VPS.
- `VPS_USER`: The username for the VPS.

Commit the workflow file and push it to the master branch to trigger the deployment process.
