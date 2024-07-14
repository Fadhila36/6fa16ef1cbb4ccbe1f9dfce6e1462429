<?php

namespace App;

use App\Mailer;
use App\RedisQueue;
use PDO;

class EmailController
{
    public function sendEmail($to, $subject, $body)
    {
        $queue = new RedisQueue();
        $queue->enqueue([
            'to' => $to,
            'subject' => $subject,
            'body' => $body
        ]);
    }

    public function processQueue()
    {
        $queue = new RedisQueue();
        $db = Database::getInstance();

        while ($email = $queue->dequeue()) {
            if (Mailer::send($email['to'], $email['subject'], $email['body'])) {
                $stmt = $db->prepare("INSERT INTO emails (to, subject, body) VALUES (:to, :subject, :body)");
                $stmt->bindParam(':to', $email['to']);
                $stmt->bindParam(':subject', $email['subject']);
                $stmt->bindParam(':body', $email['body']);
                $stmt->execute();
            }
        }
    }
}
