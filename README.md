#Battlefield 2142 Statistics Emulator Server

##Client
1. Install Battlefield 2142
2. Replace original bf2142.exe with bf2142.exe from [cracked_exe] folder (for your version)
3. (if you have installed 1.25) Edit hosts.ics ("С:\Windows\System32\drivers\etc\hosts.ics") and add next lines (сhange your.external.ip to your server's external ip):

```
your.external.ip bf2142-pc.fesl.ea.com
your.external.ip gpcm.gamespy.com
your.external.ip stella.available.gamespy.com
your.external.ip eapusher.dise.se
your.external.ip stella.prod.gamespy.com
your.external.ip stella.ms5.gamespy.com
your.external.ip ea.com
your.external.ip gamespy.com
your.external.ip messaging.ea.com
your.external.ip fesl.ea.com
your.external.ip gpsp.gamespy.com
your.external.ip gamestats.gamespy.com
your.external.ip stella.ms5.gamespy.com
your.external.ip eapusher.dice.se
```

4. (if you have installed 1.51) Edit bf2142.exe with hex-editor, change 192.168.1.3 to your server's external ip

(**NOTE**: if you are left with the unallocated space after correcting IP, set dots and change the bit-values ​​of the dots to 00)

=========

##Server

###Fesl Login Server (GameSpy Emulator)

**NOTE**: To simplify the work, use the AMP package, for example, XAMPP.

1. Install OpenSSL (>= 1.0.0). Latest version you can download [here](https://www.openssl.org/source/) .
2. Install MySQL server (latest version [here](http://dev.mysql.com/downloads/mysql/)) .
3. Import database bf2142.sql to Mysql server.
4. Edit _launch.bat. Change dbuser, dbpass, dbname.
5. Edit hosts.ics ("С:\Windows\System32\drivers\etc\hosts.ics") and add next line (сhange your.external.ip to your server's external ip):

```
your.external.ip  stella.master.gamespy.com 
```

6. Start _launch.bat

**NOTE**: MySQL should work only on localhost! Don't change dbhost from 127.0.0.1!
**NOTE**: You can change License Agreement in license.txt, but however, due to the fact that BF2142 does not know a line break, the text will be like a one-liner.