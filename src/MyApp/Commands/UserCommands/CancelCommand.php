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
use MyApp\Request;

/**
 * User "/cancel" command
 *
 * This command cancels the currently active conversation and
 * returns a message to let the user know which conversation it was.
 * If no conversation is active, the returned message says so.
 */
class CancelCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'cancel';

    /**
     * @var string
     */
    protected $description = 'Cancel the currently active auth';

    /**
     * @var string
     */
    protected $usage = '/cancel';

    /**
     * @var string
     */
    protected $version = '0.2.0';

    /**
     * @var bool
     */
    protected $need_mysql = true;

    /**
     * Command execute method
     *
     * @return \MyApp\Entities\ServerResponse
     * @throws \MyApp\Exception\TelegramException
     */
    public function execute()
    {
        $text = 'No active auth!';

        //Cancel current conversation if any
        $conversation = new Conversation(
            $this->getMessage()->getFrom()->getId(),
            $this->getMessage()->getChat()->getId()
        );

        if ($conversation_command = $conversation->getCommand()) {
            $conversation->cancel();
            $text = 'Auth "' . $conversation_command . '" cancelled!';
        }

        return $this->removeKeyboard($text);
    }

    /**
     * Remove the keyboard and output a text
     *
     * @param string $text
     *
     * @return \MyApp\Entities\ServerResponse
     * @throws \MyApp\Exception\TelegramException
     */
    private function removeKeyboard($text)
    {
        return Request::sendMessage(
            [
                'reply_markup' => Keyboard::remove(['selective' => true]),
                'chat_id'      => $this->getMessage()->getChat()->getId(),
                'text'         => $text,
            ]
        );
    }

    /**
     * Execute no db
     *
     * @return \MyApp\Entities\ServerResponse
     * @throws \MyApp\Exception\TelegramException
     */
    public function executeNoDb()
    {
        return $this->removeKeyboard('Nothing to cancel.');
    }
}
