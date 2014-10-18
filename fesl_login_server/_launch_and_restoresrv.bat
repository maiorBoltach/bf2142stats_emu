rem start fesl_login_server -x 18300 -d D:\MinGW-Windows\fesl -v -l 1 -dbhost 192.168.70.206 -dbname test2142 -dbuser us2142 -dbpass pw2142 

rem start fesl_login_server -v -l 1 -dbhost 192.168.70.206 -dbname test2142 -dbuser us2142 -dbpass pw2142 
start fesl_login_server -v -l 1 -restore_srv -dbhost 192.168.70.206 -dbname bf2142 -dbuser root -dbpass rsview32 