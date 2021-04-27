# api-assign

A simple app which run on build-in web server which interact with Fixably API. 
It will printout the data as json string by visit the different link

- Run `composer install` command
- Run php server `php -S 127.0.0.1:9002 -t ./`
- Copy .env file from `.env.example` with the code to access Fixably (add the code value)


1. Get the token: http://127.0.0.1:9002/token
2. Get order list by statuses: http://127.0.0.1:9002/orders
3. Get all order for iPhone which already assigned to technician: http://127.0.0.1:9002/iphone-orders
4. Get invoices list http://127.0.0.1:9002/invoices
5. Create a new invoice: http://127.0.0.1:9002/create-order
