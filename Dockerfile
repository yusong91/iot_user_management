FROM mysql:5.7.34

# Copy content from .sql to Docker mysql container
COPY ./iot_db.sql /docker-entrypoint-initdb.d/init.sql