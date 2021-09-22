<?php
include './vendor/autoload.php';

use Appwrite\Client;
use Appwrite\Services\Database;

function generate() {
    echo "Calculating price ...";

    $currentTime = microtime(true);

    $client = new Client();
    $client
        ->setEndpoint($_ENV['APPWRITE_ENDPOINT'])
        ->setProject($_ENV['APPWRITE_FUNCTION_PROJECT_ID'])
        ->setKey($_ENV['APPWRITE_API_KEY'])
    ;

    $database = new Database($client);

    $priceQuery = $database->listDocuments($_ENV['APPWRITE_COLLECTION_PRICE'], [], 1, 0, "createdAt", "DESC");
    $price = $priceQuery['documents'][0]['price'];

    $priceChange = rand(1, 5);
    $newCoinPrice = rand(0, 100) < 50 ? $price - $priceChange : $price + $priceChange;

    if(rand(0, 5) === 2) {
        $newCoinPrice = $price;
    }

    $database->createDocument($_ENV['APPWRITE_COLLECTION_PRICE'], [
        'price' => $newCoinPrice,
        'createdAt' => $currentTime
    ], ['*'], []);

    echo "Generated price {$newCoinPrice} at {$currentTime}";
}

for($i = 0; $i < 60; $i++) {
    generate();
    sleep(1);
}

?>