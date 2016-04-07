<?php

Use Monolog\Logger;

return [
    'hosts' => [
        '192.168.1.115:9200'
    ],
    'logPath' => 'var/log/elasticsearch',
    'logLevel' => Logger::INFO
];