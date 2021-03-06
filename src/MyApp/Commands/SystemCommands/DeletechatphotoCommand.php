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

/**
 * Delete chat photo command
 */
class DeletechatphotoCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Deletechatphoto';

    /**
     * @var string
     */
    protected $description = 'Delete chat photo';

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
        //$message = $this->getMessage();
        //$delete_chat_photo = $message->getDeleteChatPhoto();

        return parent::execute();
    }
}
