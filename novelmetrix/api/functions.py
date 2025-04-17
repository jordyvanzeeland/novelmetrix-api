from .models import Books as BooksModel
import pandas as pd

def getBooksByYear(year=None):
    if not year:
        return pd.DataFrame()
    
    data = BooksModel.objects.filter(readed__icontains=year)
    books_data = data.values()

    return pd.DataFrame.from_records(books_data)

