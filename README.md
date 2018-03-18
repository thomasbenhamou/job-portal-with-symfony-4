# job-portal-with-symfony-4
A basic job board built with Symfony 4.
See deployed version to winterjobs.herokuapp.com

Features include :
- browse job ads (by date, or category)
- register (database as a user provider)
- log in
- if logged in: able to publish new job ads
- job adverts geocoded with Gmaps API
- Gmaps integrated to show markers
- users can edit and delete their adverts

Entities:
- User: implements User Interface
- Advert: manytomany relation with Category
- Category: manytomany relation with Advert
- Image: Onetoone relation with Advert

Controllers:
- UserController : registration/log in
- AdvertController: create read update delete adverts
