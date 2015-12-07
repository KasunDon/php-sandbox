all: build

build:
	docker build -t php-sandbox-client -f docker/Dockerfile .
	
setup_store:
	docker build -t php-sandbox-store -f docker/fixtures/mongodb/Dockerfile .
	
stop_store:
	docker stop php-sandbox-store

clean_store:
	docker rm php-sandbox-store

start_store:
	docker run -d --name php-sandbox-store php-sandbox-store

start: stop
	@docker tag -f php-sandbox-common kasundon/php-sandbox-common ||:
	docker run -d --name php-sandbox-common kasundon/php-sandbox-common
	@docker tag -f php-sandbox-client kasundon/php-sandbox-client ||:
	docker run -d --name php-sandbox-client \
	    --link php-sandbox-common:sandbox-common.docker \
	    --link php-sandbox-store:mongodb-store	\
	-p 8085:80 \
	kasundon/php-sandbox-client

stop:
	@docker rm -vf php-sandbox-client php-sandbox-common||:

clean: stop
	docker rmi php-sandbox-client php-sandbox-common||:

show_log:
	docker logs php-sandbox-client
	
push:
	docker tag -f php-sandbox-client kasundon/php-sandbox-client
	docker push kasundon/php-sandbox-client

.PHONY: build start stop clean push start_store
