from redis import Redis
from rq import Queue

from providers import gc_tides_provider as gc

conn = Redis('localhost')

queue = Queue('jobs', connection=conn)


def add(fn, *args):
    return queue.enqueue(fn, *args)


def get(job_id):
    job = queue.fetch_job(job_id)
    if job is None:
        response = {'status': 'pending'}
    else:
        response = {
            'status': job.get_status(),
            'result': job.result
        }
        if job.is_failed:
            response['message'] = job.exc_info.strip().split('\n')[-1]
    return response


def handle_gc_tides(location_id):
    provider = gc.GCTidesProvider(location_id)
    provider.get_data()
    return provider.parse_predictions_tables()
