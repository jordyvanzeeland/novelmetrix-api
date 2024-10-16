import requests, json
import pandas as pd

# Get config variables
# This will be used for getting data from Google Sheets

configfile = open('config.json')
config = json.load(configfile)
year = '2023'

# Get the books of specifie reading year
# Then convert to JSON data

response = requests.get('https://sheets.googleapis.com/v4/spreadsheets/' + config['gsheet_spreadsheet'] + '/values/' + year + '!A2:Z?alt=json&key=' + config["gsheet_api_key"])
data = response.json()

# Create a dictionary of months with their numbers. 
# This is used for the month name in the Google Sheets Data
# Then create a comprehensive list of books of specified reading year.

months_indices = {"januari": "01", "februari": "02", "maart": "03", "april": "04", "mei": "05", "juni": "06", "juli": "07", "augustus": "08", "september": "09", "oktober": "10", "november": "11", "december": "12"}
booksOfYear = [{"name": book[0], "author": book[1], "genre": book[2], "readed": f'{months_indices[book[3]]}-{year}', "rating": book[4], "en": book[5]} for book in data['values']]

# Put the data in a Pandas DataFrame

df = pd.json_normalize(booksOfYear)
print(df)