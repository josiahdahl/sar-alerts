#!/bin/sh

rq worker --url http://redis:6379 default \
    & gunicorn --bind 0.0.0.0:5000 wsgi:app