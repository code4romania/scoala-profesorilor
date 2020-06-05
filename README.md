<p align="center">
    <img src="https://github.com/code4romania/talent-manager-schools/blob/master/themes/genuineq-genuineq-scoala-profesorilor/assets/img/favicon/apple-icon-180x180.png?raw=true" alt="Scoala Profesorilor" width="25%" height="25%" />
</p>

## The problem
Career path management is pretty much a foreign concept to educators and teaches in Romania. They don`t have the necessary tools available to plan their professional path with the minimum required resources. Things like professional objectives, appraisals, learning paths and much more are not a commonality among teachers.

## The solution
[Scoala Profesorilor](https://www.scoalaprofesorilor.ro) is a web application in which the teacher can create an account and configure it to the desired professional path, based on this they get a learning path recommendation (with recommended courses, accredited or not). The schools also can create an account where it can centralize all of it`s teachers and keep information up to date regarding teaching degrees, courses, or continuous training, where they can configure the desired pathway for human resources, have cost estimates and plan a training calendar.

## Technologies used

The project is developped using [October CMS](https://octobercms.com) and has [Laravel](https://laravel.com) as a foundation PHP framework.

Framework documentation as well can be found [here](https://octobercms.com/docs).

Instructions on how to install October can be found at the [installation guide](https://octobercms.com/docs/setup/installation).

Development is done with docker, using [Laradock images](https://laradock.io/).

Laradock documentation can be found [here](https://github.com/laradock/laradock).

### Local setup

For fast setup, Docker is needed.

The 'nginx', 'mysql' and 'phpmyadmin' images from laradock are used. The configuration can be fount inside 'containers' folder. To start the local containders follow next steps:
    - Clone project locally
    - Add a new entry to you 'hosts' file:
        - EX: 127.0.0.1  scoalaprofesorilot.test
    RUN: composer install && composer dump-autoload -o
    - Go to <PROJECT_DIRECTORY>/containers
    - RUN: docker up -d nginx mysql phpmyadmin
      - This will try to run the already containers and if containers don't exist it will create them
    - Check that al containers are runnin: docker ps
    - Create DB schema:
        - Go to <PROJECT_DIRECTORY>/containers
        - Connect to 'workspace' container:
            - Linux: docker-compose exec workspace bash
            - Windows: winpty docker-compose exec workspace bash
        - RUN: php artisan october:up
            - This will run all new system migrations and plugin migrations

## Development daily docker commands:

Docker build commands:
  docker build -f ./docker/Dockerfile -t tms_prod_app:latest . &&
  docker tag tms_prod_app:latest 961595780523.dkr.ecr.eu-central-1.amazonaws.com/production:tms_prod_app_<VERSION> &&
  docker push 961595780523.dkr.ecr.eu-central-1.amazonaws.com/production:tms_prod_app_<VERSION>

Docker list images:
  docker image ls

Docker run image:
  docker run --rm -it -p 80:80 <IMAGE_ID>

Docker list running containers:
  docker ps

Docker connect to container:
  docker exec -it <CONTAINER_ID> bash

## Contributing

The best place to start is by learning October and [reading the documentation](https://octobercms.com/docs), [watching some screencasts](https://octobercms.com/support/topic/screencast) or [following some tutorials](https://octobercms.com/support/articles/tutorials).

You may also watch these introductory videos for [beginners](https://vimeo.com/79963873) and [advanced users](https://vimeo.com/172202661).

## Development Team

Scoala Profesorilor was developed by [Civic Labs](https://civiclabs.ro/en) in collaboration with [Fundația Didactica Sibiu](https://scoalafinlandezasibiu.ro/) and [Genuineq](https://www.genuineq.com/).

The maintenance of Scoala Profesorilor is lead by [Code for Romania](https://code4.ro/en/), along with many wonderful people that dedicate their time to help support and grow the community.

## License

The Scoala Profesorilor platform is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
