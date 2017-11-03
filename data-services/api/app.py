from flask import Flask, request
from flask_restful import Resource, Api, reqparse

from jobs import jobs

app = Flask(__name__)
api = Api(app, '/api/v1')


class GCTides(Resource):
    def __init__(self):
        self.parser = reqparse.RequestParser()
        self.parser.add_argument('location_id', type=int)

    def get(self, job_id):
        return jobs.get(job_id), 200

    def post(self):
        """
        Takes a location id
        :return:
        """
        args = self.parser.parse_args()
        # Create a new item in the queue
        job = jobs.add(jobs.handle_gc_tides, args['location_id'])
        # return that the queue item was created successfully
        return {'id': job.id}, 201

class Jobs(Resource):
    def get(self, job_id):
        return jobs.get(job_id)


api.add_resource(GCTides, '/tides/gov-canada')
api.add_resource(Jobs, '/jobs/<job_id>')
