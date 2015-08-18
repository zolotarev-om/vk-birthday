<?php

namespace App\Repositories;

use Auth;
use Cache;

/**
 * Class MessageRepository
 * @package App\Repositories
 */
class MessageRepository
{
    /**
     * Get a list of user messages
     *
     * @return array
     */
    public function getMessageList()
    {
        $messages = Cache::remember('message_list_' . Auth::id(), 1, function () {
            return Auth::user()->messages()->get();
        });
        $messageList = [];
        foreach ($messages as $message) {
            $messageList[$message->id] = $message->text;
        }
        return $messageList;
    }

    /**
     * Get user message text by id
     *
     * @param $id
     *
     * @return mixed
     */
    public function getMessageTextById($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        $message = Auth::user()->messages()->where('id', '=', $id)->first();
        if (is_null($message)) {
            return false;
        } else {
            return $message->text;
        }
    }

    /**
     * Get one randomly a message user or create a user default message.
     *
     * @return mixed
     */
    public function getRandomMessage()
    {
        $message = Auth::user()->messages()->get();
        if ($message->isEmpty()) {
            $this->createDefaultMessage();
            $message = Auth::user()->messages()->get()->first();
            return $message;
        } else {
            $message = $message->shuffle()->first();
            return $message;
        }

    }

    /**
     * Create first/default user congratulation message
     */
    private function createDefaultMessage()
    {
        Auth::user()->messages()->create(['text' => 'С Днём Рождения!']);
    }

    /**
     * If this message does not yet have in the database that create it.
     *
     * @param $text
     *
     * @return bool
     */
    public function createMessageIfNotExist($text)
    {
        $text = filter_var($text, FILTER_SANITIZE_STRING);

        $notExist = Auth::user()->messages()->where('text', '=', $text)->get()->isEmpty();
        if ($notExist) {
            Auth::user()->messages()->create(['text' => $text]);
            Cache::forget('message_list_' . Auth::id());
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete a message from a user database on its id.
     *
     * @param $id
     *
     * @return bool|null
     */
    public function delMessageById($id)
    {
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

        if (!Auth::user()->messages()->where('id', '=', $id)->get()->isEmpty()) {
            Auth::user()->messages()->where('id', '=', $id)->delete();
            return true;
        } else {
            return null;
        }
    }
}