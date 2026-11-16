<?php

require_once "auth.php";
require_once "database.php";

// Total products
$product_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM products"
);

$total_products = mysqli_fetch_assoc($product_result)['total'];

// Total categories
$category_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM categories"
);

$total_categories = mysqli_fetch_assoc($category_result)['total'];

// Today's sales by currency
$sales_result = mysqli_query(
    $conn,
    "SELECT currency, SUM(total_amount) AS total
     FROM sales
     WHERE DATE(created_at) = CURDATE()
     GROUP BY currency"
);

$today_sales = [
    'USD' => 0,
    'LRD' => 0
];

while ($row = mysqli_fetch_assoc($sales_result)) {
    $today_sales[$row['currency']] = $row['total'];
}

// Today's expenses by currency
$expense_result = mysqli_query(
    $conn,
    "SELECT currency, SUM(amount) AS total
     FROM expenses
     WHERE DATE(created_at) = CURDATE()
     GROUP BY currency"
);

$today_expenses = [
    'USD' => 0,
    'LRD' => 0
];

while ($row = mysqli_fetch_assoc($expense_result)) {
    $today_expenses[$row['currency']] = $row['total'];
}

// Low-stock products
$low_stock_result = mysqli_query(
    $conn,
    "SELECT product_name, quantity, unit, low_stock_limit
     FROM products
     WHERE quantity <= low_stock_limit
     ORDER BY quantity ASC
     LIMIT 10"
);

// Recent sales
$recent_sales = mysqli_query(
    $conn,
    "SELECT s.sale_number, s.total_amount, s.currency,
            s.created_at, u.full_name
     FROM sales s
     JOIN users u ON s.user_id = u.id
     ORDER BY s.id DESC
     LIMIT 5"
);

// Shop name
$shop_result = mysqli_query(
    $conn,
    "SELECT shop_name FROM shop_settings WHERE id = 1"
);

$shop = mysqli_fetch_assoc($shop_result);

?>

<!DOCTYPE html>
<html>
<head>

    <title>SMARTBIZ Dashboard</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="layout">

    <?php include "includes/sidebar.php"; ?>

    <div class="main">

        <div class="header">

            <h1>Dashboard</h1>

            <p>
                Welcome, <?php
                echo htmlspecialchars($_SESSION['name']);
                ?>!
            </p>

            <h3>
                <?php
                echo htmlspecialchars(
                    $shop['shop_name'] ?? 'SMARTBIZ Grocery Store'
                );
                ?>
            </h3>

        </div>

        <div class="cards">

            <div class="card">
                <h3>Total Products</h3>
                <h2><?php echo $total_products; ?></h2>
            </div>

            <div class="card">
                <h3>Total Categories</h3>
                <h2><?php echo $total_categories; ?></h2>
            </div>

            <div class="card">
                <h3>Today's USD Sales</h3>
                <h2>$<?php echo number_format($today_sales['USD'], 2); ?></h2>
            </div>

            <div class="card">
                <h3>Today's LRD Sales</h3>
                <h2>L$<?php echo number_format($today_sales['LRD'], 2); ?></h2>
            </div>

        </div>

        <div class="cards">

            <div class="card">
                <h3>Today's USD Expenses</h3>
                <h2>$<?php echo number_format($today_expenses['USD'], 2); ?></h2>
            </div>

            <div class="card">
                <h3>Today's LRD Expenses</h3>
                <h2>L$<?php echo number_format($today_expenses['LRD'], 2); ?></h2>
            </div>

        </div>

        <div class="panel">

            <h2>Low Stock Products</h2>

            <table>

                <tr>
                    <th>Product</th>
                    <th>Available Quantity</th>
                    <th>Low Stock Limit</th>
                </tr>

                <?php if (mysqli_num_rows($low_stock_result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($low_stock_result)): ?>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['product_name']); ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row['quantity'] . ' ' . $row['unit']
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row['low_stock_limit']
                                );
                                ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="3">No low-stock products.</td>
                    </tr>

                <?php endif; ?>

            </table>

        </div>

        <div class="panel">

            <h2>Recent Sales</h2>

            <table>

                <tr>
                    <th>Sale Number</th>
                    <th>Cashier</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>

                <?php if (mysqli_num_rows($recent_sales) > 0): ?>

                    <?php while ($sale = mysqli_fetch_assoc($recent_sales)): ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($sale['sale_number']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($sale['full_name']); ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars($sale['currency']) . ' ';
                                echo number_format($sale['total_amount'], 2);
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($sale['created_at']); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="4">No sales recorded yet.</td>
                    </tr>

                <?php endif; ?>

            </table>

        </div>

    </div>

</div>

</body>
</html>