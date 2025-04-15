from django.urls import path, include
from django.views.decorators.csrf import csrf_exempt
from .views import *
from .components.books import Books

urlpatterns = [
    path('books/', Books.as_view(), name="book-list-create"),
    path('books/<int:pk>', Books.as_view(), name="book-detail")
]