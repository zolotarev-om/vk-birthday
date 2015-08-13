<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;

/**
 * Class SettingController
 * @package App\Http\Controllers
 */
class SettingController extends Controller
{
    /**
     * @var UserRepository
     */
    private $usersRep;
    /**
     * @var MessageRepository
     */
    private $messagesRep;

    /**
     * DI
     *
     * @param MessageRepository $messageRepository
     * @param UserRepository    $userRepository
     */
    public function __construct(MessageRepository $messageRepository, UserRepository $userRepository)
    {
        $this->messagesRep = $messageRepository;
        $this->usersRep = $userRepository;
    }

    /**
     * Render settings page
     *
     * @return $this
     */
    public function index()
    {
        $data['message'] = $this->messagesRep->getMessageList();
        $data['token'] = $this->usersRep->getToken();
        return view('setting')->with($data);
    }

    /**
     * Add congratulaition message to DB and refresh page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addMessage()
    {
        $msg = \Request::get('message');
        $data['result'] = [];
        if (!empty($msg)) {
            $this->messagesRep->createMessageIfNotExist($msg);
            $data['result'] = "Cообщение '$msg' добавлено";
        } else {
            return \Redirect::route('setting');
        }
        return \Redirect::route('setting')->with($data);
    }

    /**
     * Delete congratulaition message from DB and refresh page
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteMessage()
    {
        $req = \Request::all();
        $data['result'] = [];
        foreach ($req as $id => $val) {
            if (is_numeric($id) && $val == true) {
                $this->messagesRep->delMessageById($id);
                $cnt = count($data['result']);
                $data['result'][$cnt] = "сообщение с id = $id удалено";
            }
        }
        return \Redirect::route('setting')->with($data);
    }

    /**
     * Set/Update access token in DB and refresh page
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateToken()
    {
        $token = \Request::get('token');
        $data['result'] = [];
        if (!empty($token)) {
            $this->usersRep->setOrUpdateToken($token);
            $data['result'] = "Токен обновлён";
        } else {
            return \Redirect::route('setting');
        }
        return \Redirect::route('setting')->with($data);
    }
}
