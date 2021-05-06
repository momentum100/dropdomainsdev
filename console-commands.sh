php generate-certbot-cmd.php | parallel -j1
php rebuild-vhost-conf.php > /etc/apache2/sites-enabled/ssl.conf
apachectl restart