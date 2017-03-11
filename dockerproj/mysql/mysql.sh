export DB_IMPORT="https://raw.githubusercontent.com/jenca-cloud/bimportal-php/master/db_dumps/empty_portal.sql"

curl $DB_IMPORT | mysql -u root -p$MYSQL_ROOT_PASSWORD $MYSQL_DATABASE 

