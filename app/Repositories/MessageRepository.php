<?php
namespace App\Repositories;

use App\Models\Message;


class MessageRepository
{
 
    public function create(array $data)
    {
        return Message::create($data);
    }
}