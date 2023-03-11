<?php
include 'class.db.php';

$error = false;
function error($value)
{
    echo "Пожалуйста, введите $value! <br>";
    $error = true;
}

function output($db, $order = 1)
{
    $order_id = (int)$db->lastInsertId();

    $q = "SELECT * FROM orders WHERE id = :id";

    $db = Db::getInstance();
    $ret = $db->fetchOne($q, ['id' => $order_id]);

    echo "Спасибо, ваш заказ будет доставлен по адресу: " . $ret["street"] . ' ' . $ret["house"] . ' ' .
        "Номер вашего заказа:" . ' ' . $ret["id"] . ' ' .
        "Это ваш " . $order . " заказ!";

}

$db = Db::getInstance();
$name = $_GET['name'] ?? error("имя");
$phone = $_GET['phone'] ?? error("телефон");
$email = $_GET['email'] ?? error("email");
$street = $_GET['street'] ?? error("улицу");
$house = $_GET['house'] ?? error("дом");
$housing = $_GET['housing'] ?? 0;
$flat = $_GET['flat'] ?? error("квартиру");
$floor = $_GET['floor'] ?? error("этаж");

$q = "SELECT * FROM users WHERE email = :email";

$db = Db::getInstance();
$ret = $db->fetchOne($q, ['email' => $email]);

if (!$error) {
    if ($ret) {
        $q = "UPDATE users SET order_number = :new_number WHERE id = :id";

        $new_number = (int)$ret["order_number"] += 1;

        $db = Db::getInstance();
        $ret = $db->exec($q, [
            'new_number' => $new_number,
            'id' => $ret["id"],
        ]);


        $q = "INSERT INTO orders (`name`, `phone_number`, `street`, `house`, `housing` ,`flat`, `floor`, `date`)
VALUES (:name, :phone, :street, :house, :housing, :flat, :floor, :date)";

        $db = Db::getInstance();
        $ret = $db->exec($q, [
            'name' => $name,
            'phone' => $phone,
            'street' => $street,
            'house' => $house,
            'housing' => $housing,
            'flat' => $flat,
            'floor' => $floor,
            'date' => date('y-m-d'),
        ]);

        output($db, $new_number);

    } else {
        $q = "INSERT INTO users (`email`, `order_number`)
VALUES (:email, 1)";

        $db = Db::getInstance();
        $ret = $db->exec($q, [
            'email' => $email,
        ]);

        $q = "INSERT INTO orders (`name`, `phone_number`, `street`, `house`, `housing` ,`flat`, `floor`, `date`)
VALUES (:name, :phone, :street, :house, :housing, :flat, :floor, :date)";

        $db = Db::getInstance();
        $ret = $db->exec($q, [
            'name' => $name,
            'phone' => $phone,
            'street' => $street,
            'house' => $house,
            'housing' => $housing,
            'flat' => $flat,
            'floor' => $floor,
            'date' => date('y-m-d'),
        ]);

        output($db);

    }
}