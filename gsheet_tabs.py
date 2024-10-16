import requests, json
import pandas as pd

# Get config variables
# This will be used for getting data from Google Sheets

configfile = open('config.json')
config = json.load(configfile)

# Get all the tabs (sheets) of the spreadsheet
# Then convert to JSON data

response = requests.get('https://sheets.googleapis.com/v4/spreadsheets/' + config['gsheet_spreadsheet'] + '?alt=json&key=' + config["gsheet_api_key"])
data = response.json()

# Create a comprehensive list of sheet names (in thid case reading years)

sheets = [{"name": sheet['properties']['title']} for sheet in data['sheets']]

# Put the data in a Pandas DataFrame

df = pd.json_normalize(sheets)
print(df)