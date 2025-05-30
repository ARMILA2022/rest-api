<?php
require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client();

try {
    $response = $client->request('GET', 'http://omdbapi.com', [
        'query'=> [
            'apikey'=> '52ac2799',
            's'=> 'transformers',
        ]
    ]);
    $result = json_decode($response->getBody()->getContents(), true);
} catch (RequestException $e) {
    echo 'Request failed: ' . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie</title>
</head>
<body>
    <?php foreach($result ['Search'] as $movie) : ?>
    <ul>
        <li>Title : <?= $movie['Title']; ?></li>
        <li>Year : <?= $movie['Year']; ?></li>
        <li>
            <img src="<?= $movie['Poster']; ?>" width="80">
        </li>
    </ul>
    <?php endforeach; ?>
</body>
</html>