# job-portal-with-symfony-4
A basic job board built with Symfony 4.

Features include :
- browse job ads (by date, or category)
- register (database as a user provider)
- log in
- if logged in: able to publish new job ads
- job advers geocoded with Gmaps API
- Gmaps integrated to show markers

Entities:
- User: implements User Interface
- Advert: manytomany relation with Category
- Category: manytomany relation with Advert
- Image: Onetoone relation with Advert

Controllers:
- UserController : registration/log in
- AdvertController: create read update delete adverts
