import requests
import unittest
from unittest import mock
from bs4 import BeautifulSoup
from .mocks.mock_requests import mocked_requests_get
from providers.gc_tides_provider import GCTidesProvider


def mocked_table_html(url, params):
    return mocked_requests_get('''
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
                                <th scope="col" class="timezone">UTC</th>
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
''', 200)


class GCTidesProviderTestCase(unittest.TestCase):
    @mock.patch('requests.get', side_effect=mocked_table_html)
    def test_get_provider_data(self, mocked_get):
        provider = GCTidesProvider()
        provider_data = provider.get_data(7020)

        self.assertEqual(type(provider_data).__name__, 'BeautifulSoup')


if __name__ == '__main__':
    unittest.main()
