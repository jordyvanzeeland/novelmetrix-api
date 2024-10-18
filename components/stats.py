from flask import request
from components.gsheet import GSheet
from flask_jsonpify import jsonify
import pandas as pd

# ---------------------------------
# Class for calculating statistics
# ---------------------------------

class Stats:

    def __init__(self):
        self.spreadsheet = GSheet()

    # Get all the years of the spreadsheet
    # This will call the GSheet component

    def getYears(self):
        try:
            sheet = self.spreadsheet.getSheets()
            return sheet
        except Exception as e:
            return jsonify({'error': 'An error occurred: {}'.format(str(e))}, safe=False)

    # Get all the books including genres of a specified year
    # This will calculate the data to each month

    def books_per_genre_per_month(self):
        try:
            if not request.headers.get('year'):
                return jsonify("No year in header")

            sheet = self.spreadsheet.getBooks(request.headers.get('year'))
            booksPerMonth = sheet.groupby(['genre', 'readed']).size().reset_index(name='count')
            booksPerMonth = booksPerMonth.sort_values(by=['genre', 'readed', 'count'], ascending=False)
            data = booksPerMonth.to_dict(orient='records')
            return jsonify(data)

        except Exception as e:
            return jsonify({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
        
    # Get all genres of books of a specified year
    # Then it will be calculated how many books of a genre is readed

    def countGenres(self):
        try:
            if not request.headers.get('year'):
                return jsonify("No year in header")
            
            sheet = self.spreadsheet.getBooks(request.headers.get('year'))
            genres = sheet.groupby('genre')['genre'].count().reset_index(name="count")
            genres = genres.sort_values(by='count', ascending=False)
            data = [{"genre": genre, "count": int(count)} for genre, count in zip(genres['genre'], genres['count'])]
            return jsonify(data)
        except Exception as e:
            return jsonify({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
        
    # Get all ratings of books of a specified year
    # Then it will be calculated how many ratings of each amount of stars is given

    def countRatings(self):
        try:
            if not request.headers.get('year'):
                return jsonify("No year in header")
            
            sheet = self.spreadsheet.getBooks(request.headers.get('year'))
            countratings = sheet.groupby('rating')['rating'].count().reset_index(name="count")
            countratings = countratings.sort_values(by='rating', ascending=False)
            data = [{"rating": int(rating), "count": int(count)} for rating, count in zip(countratings['rating'], countratings['count'])]
            return jsonify(data)
        except Exception as e:
            return jsonify({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
        
    # Get all books of English language of a specified year
    # Then it will be calculated how many books in English is readed.
    # The rest will automatically be put in the Dutch language

    def countEnBooks(self):
        try:
            if not request.headers.get('year'):
                return jsonify("No year in header")
            
            sheet = self.spreadsheet.getBooks(request.headers.get('year'))
            countbooks = sheet.groupby('en')['en'].count().reset_index(name="count")
            countbooks = countbooks.sort_values(by='count', ascending=False)
            countbooks['lang'] = countbooks['en'].apply(lambda x: 'en' if x == "1" else 'nl')
            countbooks['name'] = countbooks['en'].apply(lambda x: 'English' if x == "1" else 'Nederlands')
            data = [{"lang": lang, "name": name, "count": int(count)} for lang, name, count in zip(countbooks['lang'], countbooks['name'], countbooks['count'])]
            return jsonify(data)
        except Exception as e:
            return jsonify({'error': 'An error occurred: {}'.format(str(e))}, safe=False)