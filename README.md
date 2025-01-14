# Реєстрація, верифікація через API  
- Реєстрація телефону перший раз **api/user/registration-phone** дані :
```
{
    "phone": "0634126106"
}
```
- Верифікація номеру за згенерованим ОТП кодом **api/user/validation-phone** дані :
```
{
    "phone": "0634126106",
    "otp_code": "8417"
}
```
- Запис іншої інформації про юзера  **api/user/enter-user-info** дані :
```
{
    "phone":"0634126106",
    "first_name": "1...",
    "second_name": "2..."
}

```