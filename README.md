# best-currency-checker
## This is an application to check best currency exchange rates near you.  
![overview](https://user-images.githubusercontent.com/37619684/160199163-e6e71aa5-5b1e-49f4-a324-f25ec941e733.gif)
[Download APK](https://github.com/TemKaa1337/best-currency-checker/raw/master/app.apk)
## Overview
Currency exchange rates are taken from ```myfin.by```.  
Currently there are 6 cities where currency exchange rates are available:  
- Grodno
- Minsk
- Gomel
- Mogilev
- Brest
- Vitebsk   
## Client  
Client of this project is written in Dart with Flutter framework. To get an APK file you need to call ```flutter build apk --release``` and the result APK file will be located at ```build/app/outputs/flutter-apk/app-release.apk```.  
## Server  
The backend of this project is written in PHP with Laravel framework. To get list of department just perform an ```POST``` request to route ```/get/nearest/departments```.  
Response example:  
```
{
    {
        "id": 41,
        "name": " Головной офис ЗАО Банк ВТБ (Беларусь) ",
        "address": "г. Минск, ул. Московская, 14",
        "phones": [
            "+375-29-309-15-15",
            "+375-33-309-15-15",
            "+375-17-309-15-15"
        ],
        "website": "www.vtb.by",
        "working_time": "Пн-Чт: 08:30-17:30 Перерыв: 13:00-13:45, Пт: 08:30-16:15 Перерыв: 13:00-13:45, Сб-Вс: Выходной",
        "coordinates": [
            "53.888609",
            "27.538472"
        ],
        "last_update": "23:27",
        "currency_info": {
            "eur": {
                "bank_buys": "3.55",
                "bank_sells": "3.7"
            },
            "usd": {
                "bank_buys": "3.2",
                "bank_sells": "3.307"
            }
        },
        "bank_name": "Банк ВТБ (Беларусь)",
        "city": "minsk",
        "created_at": "2022-03-21 11:00:09",
        "updated_at": "2022-03-21 20:41:10",
        "distance": 1700
    },
    {...}
}
```
### Errors
There are several errors you may get during perform of an API request. To get the list of departments you need to add the folowing parameters in following form:
```
{
    'location': '57.232323,37.2323',
    'radius': 6000,
    'limit': 10,
    'currency': 'usd',
    'operationType': 'bank_sells'
}
```  
If you get an error, you will get 400 or 500 status code, and the ```errors``` response parameter with array of errors.
