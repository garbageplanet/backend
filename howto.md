## Setup Docker images for development

docker inspect container_name
docker ps
docker container ls

### PostGRES
This will automatically pull the latest postgres or postgis image from docker hub
```bash
docker run -d -p 5432:5432 --name gbpn-pg-d -e POSTGRES_PASSWORD=123456 postgres-alpine -c 'listen_addresses=*'
docker run -d -p 5432:5432 --name gbpnstd -e POSTGRES_PASSWORD=123456 mdillon/postgis:11-alpine
```

Enter the database container to create a database
```bash
docker exec -it gbpn-dev-postgres bash
psql -U postgres
postgres=# CREATE DATABASE gbpnd;
postgres=# ALTER USER postgres PASSWORD '123456';
postgres=# 
postgres=# \q \d \l TABLE...
psotgres=# CREATE EXTENSION postgis;
postgres=#UPDATE garbages SET geom = ST_SetSRID(ST_MakePoint(lng, lat), 4326);

CREATE TRIGGER trigger_geom_update
    AFTER CREATE OR UPDATE ON garbages
    FOR EACH ROW
    WHEN (OLD.lat IS DISTINCT FROM NEW.lat) OR (OLD.lng IS DISTINCT FROM NEW.lng)
    EXECUTE PROCEDURE add_point_geom();

CREATE OR REPLACE FUNCTION add_point_geom()
  RETURNS trigger AS
$BODY$
BEGIN
 UPDATE garbages SET geom = ST_SetSRID(ST_MakePoint(lng, lat), 4326)
 RETURN NEW;
END;
$BODY$


```

### Redis
docker run --name gbpn-dev-redis -d -p 6379:6379 redis

###PgAdmin
This will install pgAdmin 4
```bash
docker pull dpage/pgadmin4
docker run -p 80:80 \
-e "PGADMIN_DEFAULT_EMAIL=adrien@garbagepla.net" \
-e "PGADMIN_DEFAULT_PASSWORD=123456" \
-d dpage/pgadmin4
```