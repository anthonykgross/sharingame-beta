NAME=r.cfcr.io/anthonykgross/anthonykgross/sharingame-beta

build:
	docker build --file="Dockerfile" --tag="$(NAME):master" .

install:
	docker-compose run betasharingame install

debug:
	docker run -it --rm --entrypoint=/bin/bash $(NAME):master

run:
	docker-compose up