<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MyApp\Entities\InputMessageContent;

use MyApp\Entities\InlineQuery\InlineEntity;

/**
 * Class InputVenueMessageContent
 *
 * @link https://core.telegram.org/bots/api#inputvenuemessagecontent
 *
 * <code>
 * $data = [
 *   'latitude'      => 36.0338,
 *   'longitude'     => 71.8601,
 *   'title'         => '',
 *   'address'       => '',
 *   'foursquare_id' => '',
 * ];
 * </code>
 *
 * @method float  getLatitude()          Latitude of the location in degrees
 * @method float  getLongitude()         Longitude of the location in degrees
 * @method string getTitle()             Name of the venue
 * @method string getAddress()           Address of the venue
 * @method string getFoursquareIdTitle() Optional. Foursquare identifier of the venue, if known
 *
 * @method $this setLatitude(float $latitude)                      Latitude of the location in degrees
 * @method $this setLongitude(float $longitude)                    Longitude of the location in degrees
 * @method $this setTitle(string $title)                           Name of the venue
 * @method $this setAddress(string $address)                       Address of the venue
 * @method $this setFoursquareIdTitle(string $foursquare_id_title) Optional. Foursquare identifier of the venue, if known
 */
class InputVenueMessageContent extends InlineEntity implements InputMessageContent
{

}
