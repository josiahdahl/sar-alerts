import redis
from rq import Worker, Connection
from config import REDIS_QUEUE, REDIS_HOST

if __name__ == '__main__':
    redis_connection = redis.from_url(REDIS_HOST)
    with Connection(redis_connection):
        worker = Worker(REDIS_QUEUE)
        worker.work()
