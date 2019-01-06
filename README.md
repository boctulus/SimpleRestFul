# SimpleRestFul

## Request exaplemes

## GET <READ>

GET /api/products
GET /api/products/100

## POST <CREATE>

POST /api/products

With a request body like:
{
    "name": "Vodka",
    "description": "from Bielorussia",
    "size": "2L",
    "cost": "200"
}

## DELETE

DELETE /api/products/100

## PUT  <UPDATE>

PUT /api/products/84

With a request body like:
{
    "name": "Vodka",
    "description": "from Bielorussia",
    "size": "2L",
    "cost": "200"
}

## PATCH <PARTIAL UPDATE>

PUT /api/products/84

With a request body like:
{
    "description": "from Bielorussia!",
    "cost": "230"
}

## ORDER BY

GET /api/products&order[cost]=DESC
GET /api/products&order[cost]=DESC&order[name]=ASC

## LIMIT

GET /api/products?limit=10
GET /api/products?offset=40&limit=10

## MORE EXAMPLES

GET /api/products?limit=10&order[cost]=DESC&name=Vodka