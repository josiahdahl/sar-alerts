from __future__ import absolute_import, unicode_literals
import re
import requests
from bs4 import BeautifulSoup

TIDE_URL = 'http://tides.gc.ca/eng/station'


# from .queue_handler import app


# @app.task
# def get_tide_data(id):
#


def get_content(id):
    params = {'sid': id}

    content = requests.get(TIDE_URL, params=params).content

    return BeautifulSoup(content, 'html.parser')

    # http://tides.gc.ca/eng/station?sid=7020
    # Base container - .stationTables
    # 7 day tide prediction
    # table.has_attr('title',


def high_low_test(table):
    regex = re.compile('^Times and Heights')
    return table.has_attr('title') and regex.match(table['title'])


def prediction_test(table):
    regex = re.compile('^Predicted Hourly Heights')
    return table.has_attr('title') and regex.match(table['title'])


def high_low_for_date(date):
    """Get the high and low for a date. Data is returned as
    {
        'date': '2017-10-29',
        'time': '03:44 PDT',
        'height': 3.4
        'high_low': 'high'
        'unit': 'm',
    }

    :param date:
    :return: tuple (high, low)
    """

    def map_table(data):
        return {
            'time': data.find(class_='time').text,
            'height': float(data.find(class_='heightMeters').text),
        }

    tz = date.find(class_='timezone').text

    table_rows = date.select('tbody > tr')

    tides_iterator = map(map_table, table_rows)

    tides = []

    previous_tide = None

    for tide in tides_iterator:
        if previous_tide is None:
            previous_tide = tide
        else:
            if previous_tide['height'] > tide['height']:
                tide['high_low'] = 'low'
            else:
                tide['high_low'] = 'high'

        tides.append(tide)

    if tides[1]['high_low'] == 'low':
        tides[0]['high_low'] = 'high'
    else:
        tides[0]['high_low'] = 'low'


def get_highs_and_lows(soup):
    data = soup.find_all(high_low_test)


def get_predictions(soup):
    return soup.find_all(prediction_test)


if __name__ == '__main__':
    get_content(7020)
