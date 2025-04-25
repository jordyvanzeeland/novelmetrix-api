from django.urls import path, include
from django.views.decorators.csrf import csrf_exempt
from .views import *
from .components.books import Books
from .components.pandas import *

urlpatterns = [
    path('books/', Books.as_view(), name="book-list-create"),
    path('books/<int:pk>', Books.as_view(), name="book-detail"),
    path('years/', getReadingYears),
    path('books/permonth', books_per_genre_per_month),
    path('books/genres/count', countGenres),
    path('books/ratings', countRatings),
    path('books/en', countEnBooks),
]