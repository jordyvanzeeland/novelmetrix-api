import json, requests
from flask_jsonpify import jsonify
import pandas as pd

# Google Sheets API Class 
# Where we get the data of a specified Google Spreadsheet.

class GSheet:
    
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
        config = self.getConfigFile()
        response = requests.get('https://sheets.googleapis.com/v4/spreadsheets/' + config['gsheet_spreadsheet'] + '?alt=json&key=' + config["gsheet_api_key"])
        data = response.json()

        sheets = [{"name": sheet['properties']['title']} for sheet in data['sheets']]
        df = pd.json_normalize(sheets)
        return df
    
    # Get the books of specifie reading year
    # Because the readed data in the sheet is text and is the name of the month, we also create a dictionary with the names and numbers of the months
    # Then we return the data as a Pandas DataFrame

    def getBooks(self, year):
        config = self.getConfigFile()

        response = requests.get('https://sheets.googleapis.com/v4/spreadsheets/' + config['gsheet_spreadsheet'] + '/values/' + year + '!A2:Z?alt=json&key=' + config["gsheet_api_key"])
        data = response.json()

        months_indices = {"januari": "01", "februari": "02", "maart": "03", "april": "04", "mei": "05", "juni": "06", "juli": "07", "augustus": "08", "september": "09", "oktober": "10", "november": "11", "december": "12"}
        booksOfYear = [{"name": book[0], "author": book[1], "genre": book[2], "readed": f'{months_indices[book[3]]}-{year}', "rating": book[4], "en": book[5]} for book in data['values']]
        df = pd.json_normalize(booksOfYear)
        return df
