<?php

use Aws\Exception\AwsException;
use Aws\Sqs\SqsClient;

require_once "vendor/autoload.php";

$queueUrl = "https://sqs.us-east-1.amazonaws.com/582228224341/hello_world_queue";

$client = new SqsClient([
    'profile' => 'default',
    'region' => 'us-east-1',
    'version' => '2012-11-05'
]);

try {
    $result = $client->receiveMessage([
        'QueueUrl' => $queueUrl
    ]);

    if(!empty($result->get("Messages"))) {
        echo $result->get("Messages")[0]["Body"] . PHP_EOL;

        $client->deleteMessage([
            'QueueUrl' => $queueUrl,
            'ReceiptHandle' => $result->get("Messages")[0]["ReceiptHandle"]
        ]);
    }else {
        echo "Queue without messages.\n";
    }
}catch (AwsException $e) {
    echo "Aws error: ".$e->getMessage();
}