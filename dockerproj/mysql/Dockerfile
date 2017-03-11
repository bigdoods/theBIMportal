FROM mysql:5.5

RUN apt-get update && apt-get install -y curl


# The mysql image has a one-time initdb script that checks
# for sql or script files in /docker-entrypoint-initdb.d

# Add a script that downloads and imports the sql file:

ADD mysql.sh /docker-entrypoint-initdb.d/mysql.sh

# MySQL port not exposed.  Web container connects via default network (internal)
