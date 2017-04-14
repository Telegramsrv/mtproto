<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MyApp\Entities;

/**
 * Class InlineQuery
 *
 * @link https://core.telegram.org/bots/api#inlinequery
 *
 * @method string   getId()       Unique identifier for this query
 * @method User     getFrom()     Sender
 * @method Location getLocation() Optional. Sender location, only for bots that request user location
 * @method string   getQuery()    Text of the query (up to 512 characters)
 * @method string   getOffset()   Offset of the results to be returned, can be controlled by the bot
 */
class InlineQuery extends Entity
{
    /**
     * {@inheritdoc}
     */
    protected function subEntities()
    {
        return [
            'from'     => User::class,
            'location' => Location::class,
        ];
    }
}
