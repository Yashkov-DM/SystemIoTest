# Тестовое задание SystemIo



## Getting started

- Сборка проекта осуществлена чз Docker
- Выполнена документация Swagger (NelmioApiDoc)
- Добавлено функциональное тестирование для ендпоинта /api/v1/calculate/price

## Описание endpoints

### POST /api/v1/calculate/price - расчёта цены.

- Для couponCode применена следующа кодировка (D - процентная скидка, DF - фиксированная скидка, числовое значение - величина скидки. Пример: D15 - процентная скидка 15% от стоимости) 

**Request body**:
```
{
  "product": 1,
  "taxNumber": "DE123456789",
  "couponCode": "D15"
}
```

**Responses**:

**_status 200_**
```
{
  "result": true,
  "data": {
    "price": "101.15"
  },
  "errors": []
}
```

**Responses с учётом валидации**:

**_status 200_**
```
{
  "result": false,
  "data": {
    "price": ""
  },
  "errors": {
    "taxNumber": [
      "Формат налогового номера в поле «taxNumber» не соответствует коду страны."
    ],
    "couponCode": [
      "Не верно указан номинал скидки в поле «couponCode»."
    ]
  }
}
```

### POST /api/v1/purchase - выполнение покупки.

- Ключ paymentProcessor указывает тип покупки. Инициализированы обработчики с ключами paypal и stripe 

**Request body**:
```
{
  "product": 1,
  "taxNumber": "DE123456789",
  "couponCode": "D15",
  "paymentProcessor": "paypal"
}
```

**Responses**:

- Ключ payment указывает какой тип покупки произведён. 

**_status 200_**
```
{
  "result": true,
  "data": {
    "price": "101.15",
    "payment": "paypal complete 101.15"
  },
  "errors": []
}
```

