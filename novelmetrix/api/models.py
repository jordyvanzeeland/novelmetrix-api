from django.db import models

# Create your models here.

class Books(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=255, null=False, blank=False)
    author = models.CharField(max_length=255, null=False, blank=False)
    genre = models.CharField(max_length=100, null=False, blank=False)
    readed = models.CharField(max_length=100, null=False, blank=False)
    rating = models.CharField(max_length=100, null=True, blank=True)
    en = models.IntegerField(default=0, null=False, blank=False)

    def __str__(self):
        return self.name