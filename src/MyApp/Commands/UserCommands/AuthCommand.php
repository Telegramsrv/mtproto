<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MyApp\Commands\UserCommands;

use MyApp\Commands\UserCommand;
use MyApp\Conversation;
use MyApp\Entities\Keyboard;
use MyApp\Entities\KeyboardButton;
use MyApp\Entities\PhotoSize;
use MyApp\Request;

/**
 * User "/auth" command
 */
class AuthCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'auth';

    /**
     * @var string
     */
    protected $description = 'Auth for bot users';

    /**
     * @var string
     */
    protected $usage = '/auth';

    /**
     * @var string
     */
    protected $version = '0.3.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Conversation Object
     *
     * @var \MyApp\Conversation
     */
    protected $conversation;

    /**
     * Guzzle Client object
     *
     * @var \GuzzleHttp\Client
     */
    private static $client;

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \MyApp\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat = $message->getChat();
        $user = $message->getFrom();
        $text = trim($message->getText(true));
        $chat_id = $chat->getId();
        $user_id = $user->getId();

        //Preparing Response
        $data = [
            'chat_id' => $chat_id,
        ];

        if ($chat->isGroupChat()) {
            //reply to message id is applied by default
            //Force reply is applied by default so it can work with privacy on
            $data['reply_markup'] = Keyboard::forceReply(['selective' => true]);
        }

        //Conversation start
        $this->conversation = new Conversation($user_id, $chat_id, $this->getName());

        $notes = &$this->conversation->notes;
        !is_array($notes) && $notes = [];

        //cache data from the tracking session if any
        $state = 0;
        if (isset($notes['state'])) {
            $state = $notes['state'];
        }

        $result = Request::emptyResponse();

        //State machine
        //Entrypoint of the machine state if given by the track
        //Every time a step is achieved the track is updated
        switch ($state) {
            case 0:
                if ($message->getContact() === null) {
                    $notes['state'] = 0;
                    $this->conversation->update();

                    $data['reply_markup'] = (new Keyboard(
                        (new KeyboardButton('Share Contact'))->setRequestContact(true)
                    ))
                        ->setOneTimeKeyboard(true)
                        ->setResizeKeyboard(true)
                        ->setSelective(true);

                    $data['text'] = 'Share your contact information:';

                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['phone_number'] = $message->getContact()->getPhoneNumber();
                $text          = '';

            // no break
            case 1:
                if ($text === '') {
                    $notes['state'] = 1;
                    $this->conversation->update();
                    Request::emptyResponse();

                    $number = '+'.$notes['phone_number'];

                    $entryData = array(
                        'user_id' => $user_id,
                        'code'=>'',
                        'phone_number'=>$number
                    );

                    // This is our new stuff
                    $context = new \ZMQContext();
                    $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
                    ($socket->connect("tcp://localhost:5555"));
                    ($socket->send(json_encode($entryData)));

                    $this->conversation->update();
                    $data['text'] = 'Enter the code you received: ';
                    $data['reply_markup'] = Keyboard::remove(['selective' => true]);
                    $result = Request::sendMessage($data);
                    break;
                }

                $notes['code'] = $text;
                $text             = '';

            // no break
            case 2:
                if ($text === '') {
                    $notes['state'] = 2;
                    $this->conversation->update();
                    Request::emptyResponse();
                    $code = $notes['code'];
                    $number = '+'.$notes['phone_number'];


                    $entryData = array(
                        'user_id' => $user_id,
                        'code'=>$code,
                        'phone_number'=>$number
                    );

                    // This is our new stuff
                    $context = new \ZMQContext();
                    $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
                    ($socket->connect("tcp://localhost:5555"));
                    ($socket->send(json_encode($entryData)));
                    
                    if($authorization){
                        $data['text'] = 'TRUE!!!';
                    }
                    else{
                        $data['text'] = 'LSE';
                    }

                    $result = Request::sendMessage($data);
                    $this->conversation->stop();

                    break;
                }
                $this->conversation->stop();

                break;
        }

        return $result;
    }
}
