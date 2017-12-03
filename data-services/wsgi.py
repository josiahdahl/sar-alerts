from config import DEBUG
from api.app import app
import worker


def run():
    app.run(debug=DEBUG)
