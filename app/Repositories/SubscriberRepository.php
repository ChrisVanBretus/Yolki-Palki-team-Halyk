<?php

namespace App\Repositories;

use App\Models\Subscriber;

class SubscriberRepository
{
    // Находит абонента по ID
    public function findById($id): ?Subscriber
    {
        return Subscriber::find($id);
    }

    // Создает нового абонента для пользователя
    public function create($userId): Subscriber
    {
        return Subscriber::create(['user_id' => $userId]);
    }

    // Обновляет текущего оператора для абонента
    public function updateOperator(Subscriber $subscriber, string $operator): Subscriber
    {
        $subscriber->current_operator = $operator;
        $subscriber->save();
        return $subscriber;
    }
}
