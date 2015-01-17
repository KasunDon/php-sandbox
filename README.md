## PHP Sandbox 

![Build Status](http://jenkins.phpbox.info/buildStatus/icon?job=phpbox)


### How to add PHP version

1. cd /opt/phpfarm/src
2. ./compile.sh <version>
3. create a CGI bin file for installed version /var/www/cgi-bin/php-cgi-<version>
4. edit /etc/apache2/conf.d/php-cgisetup.conf
5. chown -R www-data:www-data /var/www/cgi-bin
6. chmod -R 0744 /var/www/cgi-bin
7. set ini settings (/opt/phpfarm/inst/php-<version>/lib/php.ini) 
display_errors = on 
log_errors = on
disable_functions = "apache_child_terminate, apache_setenv, define_syslog_variables, escapeshellarg, escapeshellcmd, eval, exec, fp, fput, ftp_connect, ftp_exec, ftp_get, ftp_login, ftp_nb_fput, ftp_put, ftp_raw, ftp_rawlist, highlight_file, ini_alter, ini_get_all, ini_restore, inject_code, mysql_pconnect, openlog, passthru, php_uname, phpAds_remoteInfo, phpAds_XmlRpc, phpAds_xmlrpcDecode, phpAds_xmlrpcEncode, popen, posix_getpwuid, posix_kill, posix_mkfifo, posix_setpgid, posix_setsid, posix_setuid, posix_setuid, posix_uname, proc_close, proc_get_status, proc_nice, proc_open, proc_terminate, shell_exec, syslog, system, xmlrpc_entity_decode"

8. restart apache

9. add to phpsandbox php script