<?php
$tmpl = file_get_contents ("vhost-template.txt");
$serverIP = file_get_contents("http://169.254.169.254/metadata/v1/interfaces/public/0/ipv4/address");

if ($handle = opendir('/var/www/')) {
        while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {

                        if (gethostbyname($entry) == $serverIP) {
                        $vhost = str_replace ("DOMAIN", $entry, $tmpl);
                        echo $vhost . "\n";
                        }
                }
        }
}
closedir($handle);

