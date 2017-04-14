<?php
namespace MyApp;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    public function onSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
		echo "New connection! ({$conn->resourceId})\n";
    }
    public function onClose(ConnectionInterface $conn) {
        echo "Connection ({$conn->resourceId}) has disconnected\n";
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
		echo "Error\n";
		$conn->close();
    }
	
    /**
     * A lookup of all the obj clients have subscribed to
     */
    protected $subObj;

    public function getObj()
    {
        return $this->subObj;
    }

    public function addObj($obj)
    {
        $this->subObj = $obj;
    }
    

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
	public function broadcast($entry){
		        $entryData = json_decode($entry, true);
				var_dump($entryData);

            if (empty($entryData['code'])){
               
                $MadelineProto = false;

                echo 'Loading settings...' . PHP_EOL;
                $settings = json_decode(getenv('MTPROTO_SETTINGS'), true) ?: [];
                if ($MadelineProto === false) {
                    echo 'Loading MadelineProto...' . PHP_EOL;
                    $MadelineProto = new \danog\MadelineProto\API($settings);
                    if (getenv('TRAVIS_COMMIT') == '') {
                        $checkedPhone = $MadelineProto->auth->checkPhone(// auth.checkPhone becomes auth->checkPhone
                            [
                                'phone_number' => $entryData['phone_number'],
                            ]
                        );
                        $sentCode = $MadelineProto->phone_login($entryData['phone_number']);
                    }
                    //echo 'Wrote ' . \danog\MadelineProto\Serialization::serialize($entryData['category'].'.session.madeline', $MadelineProto) . ' bytes' . PHP_EOL;
                }
                $this->addObj(serialize($MadelineProto));

            }else{
                $code = (string)($entryData['code']);

                $MadelineProto = unserialize($this->getObj());
                $authorization = $MadelineProto->complete_phone_login($code);
                
            }

	}

}
