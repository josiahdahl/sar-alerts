import re
import requests
from bs4 import BeautifulSoup

from config import DATA_TIDES


class GCTidesProvider:
    """
    Scrapes the government of Canada website for daily tide information
    and returns it in a nice format
    """

    def __init__(self, location_id):
        self.DATA_URL = DATA_TIDES['url']
        self.location_id = location_id
        self.soup = None
        self.tides = []

    def get_data(self):
        """Get tide data for a location
        :param location_id: the location to get
        :return: a BeautifulSoup object
        """

        params = {'sid': self.location_id, 'pres': 1}
        # TODO: Catch an error if this doesn't work/fails
        data = requests.get(self.DATA_URL, params=params)
        self.soup = BeautifulSoup(data.content, 'html.parser')

    @staticmethod
    def parse_predictions_table(table):
        """Get the high and low for a date. Data is returned as
            {
                'datetime': '2017-10-29 04:00:00',
                'timezone': 'PDT',
                'height': 3.4,
                'high_low': 'high',
                'unit': 'm',
            }

            :param table:   BeautifulSoup object
            :return: array
            """

        def map_table(_date, _tz):
            return lambda data: {
                'timezone': _tz,
                'date': _date,
                'time': data.find(class_='time').text,
                'height': float(data.find(class_='heightMeters').text),
                'unit': 'm',
            }

        tz = table.find(class_='timezone').text
        date = table.select('thead')[0].text.split()[0]
        table_mapper = map_table(date, tz)

        table_rows = table.select('tbody > tr')

        tides_iterator = map(table_mapper, table_rows)

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
                previous_tide = tide

            tides.append(tide)

        if tides[1]['high_low'] == 'low':
            tides[0]['high_low'] = 'high'
        else:
            tides[0]['high_low'] = 'low'

        return tides

    def parse_predictions_tables(self):
        return list(
            map(self.parse_predictions_table,
                self.get_predictions())
        )

    def get_predictions(self):
        return self.soup.find_all(self.table_test('^Times and Heights'))

    @staticmethod
    def table_test(regex):
        test = re.compile(regex)
        return lambda table: table.has_attr('title') and test.match(table['title'])
