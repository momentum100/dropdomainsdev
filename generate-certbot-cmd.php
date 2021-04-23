<?php
$tmpl = file_get_contents ("vhost-template.txt");
$serverIP = file_get_contents("http://169.254.169.254/metadata/v1/interfaces/public/0/ipv4/address");
$i=0;


if ($handle = opendir('/var/www/')) {
        while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {

                        if (gethostbyname($entry) == $serverIP) {
   echo "certbot certonly -n --agree-tos --no-redirect --apache --register-unsafely-without-email -d $entry -w /var/www/$entry\n" ;
	$i++; 
                        }
                }
        }
}

echo $i;
closedir($handle);


