# SAR Dashboard

A modular dashboard that scrapes data/apis for relevant information and displays
it in an easy to consume way. Built with Laravel and Vue.js.

## Setup
### Development
Ensure Docker and Docker Compose are installed
```bash
cd laradock && docker-compose up -d nginx mariadb minio mailhog redis python php-worker
docker-compose exec --user=laradock workspace bash
# Now inside of the workspace container
cd backend \
    && composer install \
    && artisan migrate --seed
exit

docker-compose exec python sh
cd data-services \
    && pip install -r requirements.txt
```
