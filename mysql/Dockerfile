FROM mysql:5.5

RUN apt-get update && apt-get install -y curl

WORKDIR /docker-entrypoint-initdb.d

# The mysql image has a one-time initdb script that checks
# for sql or script files in /docker-entrypoint-initdb.d

RUN curl "https://raw.githubusercontent.com/jenca-cloud/bimportal-php/master/db_dumps/empty_portal.sql" > dump.sql

# MySQL port not exposed.  Web container connects via default network (internal)


