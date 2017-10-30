import re
import requests
from bs4 import BeautifulSoup


class GCTidesProvider:
    """
    Scrapes the government of Canada website for daily tide information
    and returns it in a nice format
    """

    def __init__(self):
        self.DATA_URL = 'http://tides.gc.ca/eng/station'

    def get_data(self, location_id):
        """Get tide data for a location
        :param location_id: the location to get
        :return: a BeautifulSoup object
        """

        params = {'sid': location_id, 'tz': 'UTC', 'pres': 1}
        # TODO: Catch an error if this doesn't work/fails
        data = requests.get(self.DATA_URL, params=params)
        return BeautifulSoup(data.content, 'html.parser')

    def

    @staticmethod
    def table_test(self, table, regex):
        test = re.compile(regex)
        return table.has_attr('title') and regex.match(table['title'])
