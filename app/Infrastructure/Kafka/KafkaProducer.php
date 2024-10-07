<?php

namespace App\Infrastructure\Kafka;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\ProducerMessage;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Throwable;

class KafkaProducer
{
    private static string $topic = '';
    private static string $key = '';
    private static array $headers = [];

    public static function send(array $message): bool
    {
        $sent = false;
        try {
            $sent = Kafka::asyncPublish(config('kafka.brokers'))
                ->onTopic(self::getTopic())
                ->withMessage(self::createMessage($message))
                ->send();

            Log::info('Message sent to Kafka.', ['sent' => $sent]);
        } catch (Throwable $e) {
            Log::error('Error publishing message on Kafka', ['error' => $e->getMessage()]);
        }

        return $sent;
    }

    public static function setKafkaKey(string $key): self
    {
        self::$key = $key;

        return new self();
    }

    public static function setHeaders(array $headers): self
    {
        self::$headers = $headers;

        return new self();
    }

    public static function setTopic(string $topic): self
    {
        self::$topic = $topic;

        return new self();
    }

    private static function getTopic(): string
    {
        return self::$topic ?? config('kafka.topics.default');
    }

    private static function getKey(): string
    {
        return self::$key ?? config('kafka.message_id_key');
    }

    private static function createMessage(array $message): ProducerMessage
    {
        return new Message(
            headers: self::$headers,
            body: $message,
            key: self::getKey()
        );
    }
}
