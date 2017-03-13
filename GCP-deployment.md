When setting up theBIMportal on google cloud platform, I recorded my steps. Please feel free to update/ open a PR with changes. Thanks.

Setup server with lamp stack using https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04

Spin up a new VM from console
$ gcloud init
$ gcloud compute instances list
$ gcloud compute ssh <server-name>

$ sudo apt-get update
$ sudo apt-get install apache2
$ sudo apt-get install mysql-server php5-mysql

set Password = <your-pw>

$ sudo mysql_install_db
$ sudo mysql_secure_installation
$ sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt
$ sudo vim /etc/apache2/mods-enabled/dir.conf

In VIM, move the "index.php" to the first option to load straight after "DirectoryIndex"

$ sudo service apache2 restart

Test to see if all is good:
$ sudo vim /var/www/html/info.php

Enter the following PHP code:

```
  <?php
  phpinfo();
  ?>
```

All good? Remove it! It can leak info to the web.

$ sudo rm /var/www/html/info.php

Give user rights to write to www folder on server - Explained further here :http://stackoverflow.com/questions/27807018/gcloud-compute-copy-files-permission-denied-when-copying-files

$ sudo mv /var/www/html /var/www/html2
$ sudo chown -R $USER /var/www/

$ exit

to copy files to your remote server run:
$ gcloud compute copy-files ~/<path-to>/<your-local-BIMportal-project-directory>/<local.dev> YOURUSER@INSTANCENAME:/var/www/


Setup sql to read the empty_portal dump file.
Hit URL and check that there is a db error for lolz.

Just to make sure that user has correct permissions to access mysql:
$ cd /var/lib
$ chown mysql:mysql mysql/ -R

$ mysql -u root -p

$ SHOW databases;
$ CREATE DATABASE empty_portal;
$ USE empty_portal;
$ SOURCE /var/www/html/db_dumps/empty_portal.sql
$ exit

Now onto config of application so that application can access db.

$ cd /var/www/html

Update application/config/database.php with access details you have setup for mysql

enable rewrite on virtual host:
$ sudo a2enmod rewrite

$ sudo vim /etc/apache2/apache2.conf

edit apache.conf so that it allows overrides and matches the following:

```
  <Directory />
          Options FollowSymLinks
          AllowOverride All
          Require all denied
  </Directory>

  <Directory /usr/share>
          AllowOverride None
          Require all granted
  </Directory>

  <Directory /var/www/>
          Options Indexes FollowSymLinks
          AllowOverride All
          Require all granted
  </Directory>
```

Make sure .htaccess file is present in document root directory.

Install phpmyadmin
$  sudo apt-get update
$  sudo apt-get install phpmyadmin
$  sudo php5enmod mcrypt
$  sudo service apache2 restart

$  sudo service apache2 restart

Go!


### For reference only - My console command HISTORY:
   43  mkdir html
   44  cp -r bimportal-vfl/html/* html/
   45  cd /var/lib/
   46  ls -la
   47  chown mysql:mysql mysql/ -R
   48  sudo chown mysql:mysql mysql/ -R
   49  ls -la
   50  mysql -u root -p
   51  cd /var/www/html
   52  ls
   53  sudo vim application/config/database.php
   54  sudo a2enmod rewrite
   55  sudo service apache2 restart
   56  ls
   57  cd /etc/apache2/
   58  ls
   59  sudo vim apache2.conf
   60  sudo service apache2 restart
   61  sudo vim apache2.conf
   62  sudo a2enmod rewrite
   63  sudo service apache2 restart
   64  sudo vim sites-available/000-default.conf
   65  sudo vim apache2.conf
   66  sudo service apache2 restart
   67  cd /var/www/
   68  ls
   69  ls html
   70  cd html
   71  ls -la
   72  sudo vim .htaccess
   73  sudo service apache2 restart
   74  sudo vim .htaccess
   75  sudo service apache2 restart
   76  sudo vim .htaccess
   77  sudo vim application/.htaccess
   78  ls -la
   79  sudo chown -R bigdoods /var/www/html/.htaccess
   80  ls -la
   81  rm .htaccess
   82  touch .htaccess
   83  vim .htaccess
   84  ls -la
   85  sudo a2enmod rewrite
   86  sudo service apache2 restart
   87  cd /etc/apache2/
   88  ls -la
   89  vim apache2.conf
   90  vim sites-enabled/
   91  vim sites-enabled/000-default.conf
   92  ls mods-enabled/
   93  sudo service apache2 restart
   94  cd /var/www/html/
   95  ls
   96  ls -la
   97  mv .htaccess lol
   98  ls
   99  vim lol
  100  sudo service apache2 restart
  101  mv lol .htaccess
  102  sudo service apache2 restart
  103  vim .htaccess
  104  sudo service apache2 restart
