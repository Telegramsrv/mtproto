<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MyApp\Commands\SystemCommands;

use MyApp\Commands\SystemCommand;
use MyApp\Request;

/**
 * New chat member command
 */
class NewchatmemberCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Newchatmember';

    /**
     * @var string
     */
    protected $description = 'New Chat Member';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws \MyApp\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        var_dump($message);

        $chat_id = $message->getChat()->getId();
        $member  = $message->getNewChatMember();
        $text    = 'Hi there!';

        if (!$message->botAddedInChat()) {
            $text = 'Hi ' . $member->tryMention() . '!';
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::emptyResponse();

        //return Request::sendMessage($data);
    }
}
