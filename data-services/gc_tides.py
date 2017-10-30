from __future__ import absolute_import, unicode_literals
import re
import requests
from bs4 import BeautifulSoup

TIDE_URL = 'http://tides.gc.ca/eng/station'


# from .queue_handler import app


# @app.task
# def get_tide_data(id):
#
