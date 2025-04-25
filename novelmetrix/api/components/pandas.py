from rest_framework.decorators import api_view
import pandas as pd
from rest_framework.response import Response
from django.http import JsonResponse
from ..functions import getBooksByYear
from ..models import Books as BooksModel

# ----------------------
# Get all reading years
# ----------------------

@api_view(['GET'])
def getReadingYears(request):
    try:
        books = BooksModel.objects.filter()
        books_data = books.values()

        df = pd.DataFrame.from_records(books_data)
        df['readed'] = pd.to_datetime(df['readed'], errors='coerce')
        df['year']= df['readed'].dt.year
        years = df.groupby('year')['year'].count().reset_index(name="count")

        return Response(years['year'])

    except Exception as e:
        return JsonResponse({'error': 'An error occurred: {}'.format(str(e))}, safe=False)

# ------------------------------------------------------------------
# Get books of selected year and filter it per month and per genre
# ------------------------------------------------------------------

@api_view(['GET'])
def books_per_genre_per_month(request):
    try:
        if not request.META.get('HTTP_YEAR'):
            return JsonResponse({'error': 'No year in header'}, safe=False)
        
        df = getBooksByYear(request.META.get('HTTP_YEAR'));
        df['readed'] = pd.to_datetime(df['readed'], format='%Y-%m-%d')
        df['readed'] = df['readed'].dt.strftime('%m-%Y')

        booksPerMonth = df.groupby(['genre', 'readed']).size().reset_index(name='count')
        booksPerMonth = booksPerMonth.sort_values(by=['genre', 'readed', 'count'], ascending=False)
        data = booksPerMonth.to_dict(orient='records')
        return Response(data)

    except Exception as e:
        return JsonResponse({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
    
# ---------------------------------------------
# Get genres of selected year with percentages
# ---------------------------------------------

@api_view(['GET'])
def countGenres(request):
    try:
        if not request.META.get('HTTP_YEAR'):
            return JsonResponse({'error': 'No year in header'}, safe=False)
        
        df = getBooksByYear(request.META.get('HTTP_YEAR'));
        genres = df.groupby('genre')['genre'].count().reset_index(name="count")
        genres = genres.sort_values(by='count', ascending=False)
        data = [{"genre": genre, "count": int(count)} for genre, count in zip(genres['genre'], genres['count'])]
        return Response(data)

    except Exception as e:
        return JsonResponse({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
    
# -----------------------------
# Get ratings of selected year
# -----------------------------

@api_view(['GET'])
def countRatings(request):
    try:
        if not request.META.get('HTTP_YEAR'):
            return JsonResponse({'error': 'No year in header'}, safe=False)
        
        df = getBooksByYear(request.META.get('HTTP_YEAR'));
        countratings = df.groupby('rating')['rating'].count().reset_index(name="count")
        countratings = countratings.sort_values(by='rating', ascending=False)
        data = [{"rating": int(rating), "count": int(count)} for rating, count in zip(countratings['rating'], countratings['count'])]
        return Response(data)

    except Exception as e:
        return JsonResponse({'error': 'An error occurred: {}'.format(str(e))}, safe=False)
    
# -----------------------------
# Count EN and NL books
# -----------------------------

@api_view(['GET'])
def countEnBooks(request):
    try:
        if not request.META.get('HTTP_YEAR'):
            return JsonResponse({'error': 'No year in header'}, safe=False)
        
        df = getBooksByYear(request.META.get('HTTP_YEAR'));
        countbooks = df.groupby('en')['en'].count().reset_index(name="count")
        countbooks = countbooks.sort_values(by='count', ascending=False)
        countbooks['lang'] = countbooks['en'].apply(lambda x: 'en' if x == 1 else 'nl')
        countbooks['name'] = countbooks['en'].apply(lambda x: 'English' if x == 1 else 'Nederlands')
        data = [{"lang": lang, "name": name, "count": int(count)} for lang, name, count in zip(countbooks['lang'], countbooks['name'], countbooks['count'])]

        return Response(data)

    except Exception as e:
        return JsonResponse({'error': 'An error occurred: {}'.format(str(e))}, safe=False)