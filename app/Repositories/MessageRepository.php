<?php

namespace App\Repositories;

use Auth;

/**
 * Class MessageRepository
 * @package App\Repositories
 */
class MessageRepository
{
    /**
     * @return array
     */
    public function getMessageList()
    {
        $messages = Auth::user()->messages()->get();
        $messageList = [];
        foreach ($messages as $message) {
            $messageList[$message->id] = $message->text;
        }
        return $messageList;
    }

    /**
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
     * Create first/default congratulation message
     */
    private function createDefaultMessage()
    {
        Auth::user()->messages()->create(['text' => 'С Днём Рождения!']);
    }

    /**
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
            return true;
        } else {
            return false;
        }
    }

    /**
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