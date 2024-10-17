from flask import Flask
from flask_cors import CORS
from stats import stats

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

app.register_blueprint(stats, url_prefix='/stats')

if __name__ == "main":
    app.run(host="0.0.0.0", debug=True)