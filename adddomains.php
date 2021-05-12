<?php
$baseDir = "/var/www/";
$n = "<br>\n";

if ($_POST['submit']) {
    $ip = $_POST['ip'];
    $api = $_POST['api'];

	$domains = explode ("\n", $_POST['domains']);
	
	for ($i=0; $i< count($domains);$i++) {
		$domain = trim($domains[$i]);
		$cmd = "curl -X POST -H \"Content-Type: application/json\" -H \"Authorization: Bearer ".$api."\" -d '{\"name\":\"".$domain."\",\"ip_address\":\"$ip\"}' \"https://api.digitalocean.com/v2/domains\"";
			
		echo "<b> $i ". $domain. "</b>";
		// run curl command;
		echo `$cmd` . $n;
		// create link to donor
		//$cmd = "ln -s ". $_POST['donorDir'] . " /var/www/". $domain;
		
		// create domain directory
		$cmd = "mkdir /var/www/". $domain;
		echo $cmd . $n;
		`$cmd`;

		// copy contents from donor to domain directory
		$cmd = "cp -R ". $_POST['donorDir'] . "/. /var/www/". $domain . "/";
		echo $cmd . $n;`$cmd`;
	}

        sleep(3);

		
	// CHECK DOMAIN VALIDITY
	echo "VALID DOMAINS LIST: $n NB! REBUILD CERTBOT AND APACHE CONF$n";
	for ($i=0; $i< count($domains);$i++) {
        $domain = trim($domains[$i]);
        $serverIP = file_get_contents("http://169.254.169.254/metadata/v1/interfaces/public/0/ipv4/address");
		
		if (gethostbyname ($domain) == $serverIP) {
			echo "https://". $domain . $n;
		}
	}
		//certbot commands
       for ($k=0; $k< count($domainList); $k++) {
                echo "certbot certonly -n --no-redirect --apache --register-unsafely-without-email -d ".$domainList[$k]." -w /var/www/".$domainList[$k] . $n.$n;

       }

		
	//print_r($domains);
	
}
?>

<html>
<body>
<form action=adddomains.php method=POST>

<select name=donorDir>
<?php
if ($handle = opendir('/var/www/donors/')) {
	while (false !== ($entry = readdir($handle)) ) {
		if ($entry != "." && $entry != "..") {
			echo "<option value='/var/www/donors/". $entry . "'>$entry</option>";
		}
	}
}
?>
</select>
<br>
server IP: <input type=text name=ip value="<?php readfile("http://169.254.169.254/metadata/v1/interfaces/public/0/ipv4/address"); ?>"><br>
API key:  <input type=text name=api value="APIKEYHERE"><br>
<br>
<!-- <input type=text name=donorDir value="/var/www/donors/finance"><br> -->
<br>
Domains to add:
<br>
<textarea name=domains rows=20></textarea><br>
<input type=submit name=submit>
</form>
