NAME=r.cfcr.io/anthonykgross/anthonykgross/sharingame-beta

build:
	docker build --file="Dockerfile" --tag="$(NAME):master" .

install:
	docker-compose run betasharingame install

debug:
	docker-compose run betasharingame bash

run:
	docker-compose up
