FROM mysql:8.0
COPY ./db-init.sql /docker-entrypoint-initdb.d/
CMD ["mysqld"]