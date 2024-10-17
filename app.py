from flask import Flask
from flask_cors import CORS

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

if __name__ == "main":
    app.run(host="0.0.0.0", debug=True)