<?php
namespace App\Repositories;

use App\Models\Message;


class MessageRepository
{
 
    public function create(array $data)
    {
        return Message::create($data);
    }


    public function count()
    {
        return Message::count();
    }

    public function all($nbr)
    {
        return Message::latest()->paginate($nbr);
    }


    public function getAd($message)
    {
        return $message->ad()->firstOrFail();
    }

    public function delete($message)
    {
        $message->delete();
    }


    public function getById($id)
    {
        return Message::findOrFail($id);
    }
}