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
 * Edited channel post command
 */
class EditedchannelpostCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'Editedchannelpost';

    /**
     * @var string
     */
    protected $description = 'Handle edited channel post';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Execute command
     *
     * @return mixed
     * @throws \MyApp\Exception\TelegramException
     */
    public function execute()
    {
        //$edited_channel_post = $this->getUpdate()->getEditedChannelPost();

        return parent::execute();
    }
}
