import requests
import unittest
from unittest import mock
from bs4 import BeautifulSoup
from .mocks.mock_requests import mocked_requests_get
from providers.gc_tides_provider import GCTidesProvider

mock_html = '''
<table style="width:100%!important" title="Times and Heights for High and Low Tides 2017-10-29">
                        <thead>
                            <tr>
                                <th colspan="3">
                                    2017-10-29<br>
                                    (Sunday)
                                </th>
                            </tr>
                            <tr>
                                <th scope="col">Time</th>
                                <th scope="col" colspan="2">Height</th>
                            </tr>
                            <tr>
                                <th scope="col" class="timezone">PDT</th>
                                <th scope="col" class="heightMeters">(m)</th>
                                <th scope="col" class="heightFeet">(ft)</th>
                            </tr>
                        </thead>
                        <tbody>
                                <tr>
                                    <td class="time">02:08</td>
                                    <td class="heightMeters">2.3</td>
                                    <td class="heightFeet">7.5</td>
                                </tr>
                                <tr>
                                    <td class="time">10:44</td>
                                    <td class="heightMeters">1.2</td>
                                    <td class="heightFeet">3.9</td>
                                </tr>
                                <tr>
                                    <td class="time">18:36</td>
                                    <td class="heightMeters">2.5</td>
                                    <td class="heightFeet">8.2</td>
                                </tr>
                        </tbody>
                    </table>
'''


def mocked_table_html(url, params):
    return mocked_requests_get(mock_html, 200)


class GCTidesProviderTestCase(unittest.TestCase):
    @mock.patch('requests.get', side_effect=mocked_table_html)
    def test_get_provider_data(self, mocked_get):
        provider = GCTidesProvider(7020)
        provider.get_data()

        self.assertEqual(type(provider.soup).__name__, 'BeautifulSoup')

    def test_parse_predictions_table(self):
        provider = GCTidesProvider(7020)
        table = BeautifulSoup(mock_html, 'html.parser')

        result = provider.parse_predictions_table(table)

        expected_result = [{
            'datetime': '2017-10-29 02:08:00',
            'timezone': 'PDT',
            'height': 2.3,
            'high_low': 'high',
            'unit': 'm',
        }, {
            'datetime': '2017-10-29 10:44:00',
            'timezone': 'PDT',
            'height': 1.2,
            'high_low': 'low',
            'unit': 'm',
        }, {
            'datetime': '2017-10-29 18:36:00',
            'timezone': 'PDT',
            'height': 2.5,
            'high_low': 'high',
            'unit': 'm',
        }]

        self.assertEqual(result, expected_result)

    if __name__ == '__main__':
        unittest.main()
