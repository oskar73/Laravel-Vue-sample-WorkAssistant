<?php


namespace App\Integration;

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class SSH
{
    public $ip;
    public $username;
    public $password;
    public $ssh;
    public $ssh_port;
    public $status;

    public function __construct()
    {
        $ssh = optional(option("ssh", []));
        $this->ip = $ssh['ip'];
        $this->ssh_port = $ssh['port'];
        $this->username = $ssh['username'];
        $this->ssh = new SSH2($this->ip, $this->ssh_port);

        $key = new RSA();
        $key->loadKey($ssh['key']);
        $this->status = $this->ssh->login($this->username, $key);
    }

    public function exec($command)
    {
        if ($this->status) {
            return $this->ssh->exec($command);
        }

        return 0;
    }
    public function addAliasAndSSL($domain)
    {
        $txt = "server {
            listen 80;
            listen [::]:80;
            server_name $domain www.$domain;

            if (\$host = $domain) {
                return 301 https://\$server_name\$request_uri;
            }
        }

        server {

            listen 443 ssl;
            server_name $domain www.$domain;

            root /home/forge/fortify.tk/public;

            add_header X-Frame-Options \"SAMEORIGIN\";
            add_header X-XSS-Protection \"1; mode=block\";
            add_header X-Content-Type-Options \"nosniff\";

            index index.html index.htm index.php;

            charset utf-8;

            location / {
                try_files \$uri \$uri/ /index.php?\$query_string;
            }

            location = /favicon.ico { access_log off; log_not_found off; }
            location = /robots.txt  { access_log off; log_not_found off; }

            error_page 404 /index.php;

            location ~ \.php$ {
                fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
                fastcgi_index index.php;
                fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
                include fastcgi_params;
                fastcgi_read_timeout 300;
            }

            location ~ /\.(?!well-known).* {
                deny all;
            }
        }
    ";
        $command = "cd /home/forge/fortify.tk/Nginx && echo '$txt' > $domain;"; //nginx config add
        $command .= "echo $this->password | sudo -S certbot -d $domain -d www.$domain --no-redirect 2>&1"; //add ssl using letsencrypt certbot

        return $this->exec($command);
    }
    public function removeAliasAndSSL($domain)
    {
        $command = "cd /home/forge/fortify.tk/Nginx && rm -rf $domain;"; //remove nginx config
        $command .= "echo $this->password | sudo -S certbot delete --cert-name $domain;"; //remove letsencrypt

        return $this->exec($command);
    }
}
