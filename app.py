from flask import Flask
from flask_cors import CORS
from components.stats import Stats

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

getStats = Stats()

@app.route('/years')
def getYears():
    return getStats.getYears()

@app.route('/stats/books/permonth')
def getBooksPerMonth():
    return getStats.books_per_genre_per_month()

@app.route('/stats/books/genres')
def getGenres():
    return getStats.countGenres()

@app.route('/stats/books/ratings')
def getRatings():
    return getStats.countRatings()

@app.route('/stats/books/en')
def getEnBooks():
    return getStats.countEnBooks()

if __name__ == "main":
    app.run(host="0.0.0.0", debug=True)