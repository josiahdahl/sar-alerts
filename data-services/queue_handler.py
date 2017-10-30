from __future__ import absolute_import, unicode_literals
from celery import Celery

app = Celery('data-services',
             broker='amqp://sarqueue:devpassword1234@localhost:5672/sarqueue',
             include=['data-services.gc_tides'])

if __name__ == '__main__':
    app.start()
