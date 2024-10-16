import json, requests
from flask_jsonpify import jsonify
import pandas as pd

# ------------------------------------------------------------
# Google Sheets API Class 
# Where we get the data of a specified Google Spreadsheet.
# ------------------------------------------------------------

class GSheet:

    # Initialising self variables 
    # Can be used through the class

    def __init__(self):
        self.config = self.getConfigFile()
        self.gsheet_url = "https://sheets.googleapis.com/v4/spreadsheets"
        self.gsheet_id = self.config['gsheet_spreadsheet']
        self.gsheet_apikey = self.config['gsheet_api_key']
    
    # Get config variables
    # This will be used for getting data from Google Sheets
    
    def getConfigFile(self):
        try:
            configfile = open('config.json')
            data = json.load(configfile)
            return data
        except Exception as e:
            return jsonify("Error while loading the data: {}".format(e))
        
    # Get all the tabs (sheets) of the spreadsheet
    # Then we return the data as a Pandas DataFrame
        
    def getSheets(self):
        try:
            response = requests.get(self.gsheet_url + '/' + self.gsheet_id + '?alt=json&key=' + self.gsheet_apikey)
            data = response.json()

            sheets = [{"name": sheet['properties']['title']} for sheet in data['sheets']]
            return sheets
        except Exception as e:
            return jsonify("Error while loading the sheets: {}".format(e))
        
    # Get the books of specifie reading year
    # Because the readed data in the sheet is text and is the name of the month, we also create a dictionary with the names and numbers of the months
    # Then we return the data as a Pandas DataFrame

    def getBooks(self, year):
        try:
            response = requests.get(self.gsheet_url + '/' + self.gsheet_id + '/values/' + year + '!A2:Z?alt=json&key=' + self.gsheet_apikey)
            data = response.json()

            months_indices = {"januari": "01", "februari": "02", "maart": "03", "april": "04", "mei": "05", "juni": "06", "juli": "07", "augustus": "08", "september": "09", "oktober": "10", "november": "11", "december": "12"}
            booksOfYear = [{"name": book[0], "author": book[1], "genre": book[2], "readed": f'{months_indices[book[3]]}-{year}', "rating": book[4], "en": book[5]} for book in data['values']]
            df = pd.json_normalize(booksOfYear)
            return df
        except Exception as e:
            return jsonify("Error while loading the books: {}".format(e))
