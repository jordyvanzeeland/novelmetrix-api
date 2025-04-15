from rest_framework.views import APIView
from rest_framework.response import Response
from rest_framework import status
from ..models import Books as BooksModel
from ..serializers import BooksSerializer

class Books(APIView):
    
    # GET: Get books from the database of a specified user
    # Also function for getting a specific book by it's id
    # Including filters for year and challengeid. Standard filtering on userid

    def get(self, request, pk=None):
        if pk:
            try:
                book = BooksModel.objects.get(pk=pk)
                serializer = BooksSerializer(book)
                return Response(serializer.data)
            except BooksModel.DoesNotExist:
                return Response(status=status.HTTP_404_NOT_FOUND)
            
        # Handle filtering based on headers
            
        filter_params = {}
            
        if request.headers.get('year'):
            filter_params['readed__icontains'] = request.headers.get('year')

        # Retrieve and serialize books based on the filter
            
        books = BooksModel.objects.filter(**filter_params)
        serializer = BooksSerializer(books, many=True)
        return Response(serializer.data)
    
    # POST: Insert a book to the database

    def post(self, request):
        serializer = BooksSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data, status=status.HTTP_201_CREATED)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    # PUT: Edit a book in the database
    # Inclusing a possibility for partial update
    # Partial: update only the columns that are found in the request data

    def put(self, request, pk):
        try:
            book = BooksModel.objects.get(pk=pk)
        except BooksModel.DoesNotExist:
            return Response(status=status.HTTP_404_NOT_FOUND)
        
        serializer = BooksSerializer(book, data=request.data, partial=True)
        if serializer.is_valid():
            serializer.save()
            return Response(serializer.data)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

    # DELETE: Delete a book from the database

    def delete(self, request, pk):
        try:
            book = BooksModel.objects.get(pk=pk)
            book.delete()
            return Response(status=status.HTTP_204_NO_CONTENT)
        except BooksModel.DoesNotExist:
            return Response(status=status.HTTP_404_NOT_FOUND)