<?php

$products = [
    1 => ["Молоко пастеризоване", 12],
    2 => ["Хліб чорний", 9],
    3 => ["Сир білий", 21],
    4 => ["Сметана 20%", 25],
    5 => ["Кефір 1%", 19],
    6 => ["Вода газована", 18],
    7 => ['Печиво "Весна"', 14]
];

$cart = [];
$userProfile = ["name" => null, "age" => null];

function printName() {
    echo "\n################################\n";
    echo "# ПРОДОВОЛЬЧИЙ МАГАЗИН \"ВЕСНА\" #\n";
    echo "################################\n";
}

function printMainMenu() {
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
}

function inputCommand() {
    global $stdin;
    while (true) {
        echo "Введіть команду: ";
        $cmd = trim(fgets($stdin));
        if (in_array($cmd, ['0', '1', '2', '3'])) {
            return (int)$cmd;
        }
        echo "ПОМИЛКА! Введіть правильну команду\n";
        printMainMenu();
    }
}

function showProducts() {
    global $products;
    echo "\n№  НАЗВА                 ЦІНА\n";
    foreach ($products as $number => $data) {
        printf("%-2d %-21s %d\n", $number, $data[0], $data[1]);
    }
    echo "   -----------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
}

function shoppingMode() {
    global $products, $cart, $stdin;

    while (true) {
        showProducts();
        echo "Виберіть товар: ";
        $choice = trim(fgets($stdin));
        if (!is_numeric($choice)) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }
        $choice = (int)$choice;

        if ($choice === 0) break;

        if (!array_key_exists($choice, $products)) {
            echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
            continue;
        }

        [$name, $price] = $products[$choice];
        echo "Вибрано: $name\n";
        echo "Введіть кількість, штук: ";
        $qty = trim(fgets($stdin));

        if (!is_numeric($qty)) {
            echo "ПОМИЛКА! Кількість має бути числом\n";
            continue;
        }

        $qty = (int)$qty;

        if ($qty < 0 || $qty > 99) {
            echo "ПОМИЛКА! Кількість має бути від 0 до 99\n";
            continue;
        }

        if ($qty === 0) {
            if (isset($cart[$choice])) {
                unset($cart[$choice]);
                echo "ВИДАЛЯЮ З КОШИКА\n";
            }
            if (empty($cart)) {
                echo "КОШИК ПОРОЖНІЙ\n";
            }
        } else {
            $cart[$choice] = $qty;
            echo "\nУ КОШИКУ:\nНАЗВА        КІЛЬКІСТЬ\n";
            foreach ($cart as $pid => $qty) {
                echo str_pad($products[$pid][0], 13) . " $qty\n";
            }
        }
    }
}

function showReceipt() {
    global $cart, $products;

    if (empty($cart)) {
        echo "КОШИК ПОРОЖНІЙ\n";
        return;
    }

    echo "\n№  НАЗВА                 ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
    $total = 0;
    $i = 1;
    foreach ($cart as $pid => $qty) {
        [$name, $price] = $products[$pid];
        $cost = $price * $qty;
        $total += $cost;
        printf("%-2d %-21s %-5d %-9d %d\n", $i++, $name, $price, $qty, $cost);
    }
    echo "РАЗОМ ДО CПЛАТИ: $total\n";
}

function setupProfile() {
    global $userProfile, $stdin;

    while (true) {
        echo "Ваше імʼя: ";
        $name = trim(fgets($stdin));
        if (preg_match('/[a-zа-яіїєґ]/iu', $name)) {
            break;
        }
        echo "ПОМИЛКА! Імʼя повинно містити хоча б одну літеру.\n";
    }

    while (true) {
        echo "Ваш вік: ";
        $age = trim(fgets($stdin));
        if (is_numeric($age) && $age >= 7 && $age <= 150) {
            break;
        }
        echo "ПОМИЛКА! Вік повинен бути між 7 та 150.\n";
    }

    $userProfile["name"] = $name;
    $userProfile["age"] = (int)$age;
    echo "Профіль збережено: $name, $age років\n";
}

$stdin = fopen("php://stdin", "r");

function main() {
    while (true) {
        printName();
        printMainMenu();
        $cmd = inputCommand();
        if ($cmd === 1) {
            shoppingMode();
        } elseif ($cmd === 2) {
            showReceipt();
        } elseif ($cmd === 3) {
            setupProfile();
        } elseif ($cmd === 0) {
            echo "До побачення!\n";
            break;
        }
    }
}

main();
