SMARTBIZ - LESSON 17: CUSTOMER MANAGEMENT

FILES
- customers/index.php  View, search and delete customers
- customers/add.php    Add a customer
- customers/edit.php   Edit customer details
- customers/delete.php Protected optional delete endpoint
- database/lesson17_customer_migration.sql  Database changes

INSTALLATION
1. Make a backup of your smartbiz database.
2. Open XAMPP and start Apache and MySQL.
3. Open http://localhost/phpmyadmin
4. Select the smartbiz database.
5. Open the SQL tab and run database/lesson17_customer_migration.sql.
6. Copy the customers folder into:
   C:\xampp\htdocs\smartbiz\customers\
7. Open:
   http://localhost/smartbiz/customers/index.php

SIDEBAR
Add this link to includes/sidebar.php where the other menu links are:
<a href="/smartbiz/customers/index.php">Customers</a>

ACCESS
Only owner and cashier roles can open this module.

IMPORTANT
The customer_id field is added to sales so that a future POS lesson can attach
a customer to a sale. This lesson does not yet add customer selection to POS.
Existing sales remain unchanged and customer_id will be NULL.

TEST
- Add a customer with name, phone, email and address.
- Search by name or phone.
- Edit the customer.
- Delete a test customer.
- Confirm the customer list updates.
