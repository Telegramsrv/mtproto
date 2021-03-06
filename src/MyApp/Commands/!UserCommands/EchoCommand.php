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
use MyApp\Request;

/**
 * User "/echo" command
 */
class EchoCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'echo';

    /**
     * @var string
     */
    protected $description = 'Show text';

    /**
     * @var string
     */
    protected $usage = '/echo <text>';

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
        $chat_id = $message->getChat()->getId();
        $text    = trim($message->getText(true));

        if ($text === '') {
            $text = 'Command usage: ' . $this->getUsage();
        }

        $data = [
            'chat_id' => $chat_id,
            'text'    => $text,
        ];

        return Request::sendMessage($data);
    }
}
