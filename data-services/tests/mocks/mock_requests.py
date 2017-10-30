def mocked_requests_get(*args, **kwargs):
    class MockResponse:
        def __init__(self, html, status_code):
            self.content = html
            self.status_code = status_code

    return MockResponse(*args)
