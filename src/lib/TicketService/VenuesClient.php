<?php
namespace TicketService;

require_once 'Generated/Types.php';
require_once 'Generated/Venues.php';

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Protocol\TCompactProtocol;
use Thrift\Transport\TBufferedTransport;
use Thrift\Transport\TFramedTransport;
use Thrift\Transport\TSocket;
use Thrift\Transport\TTransport;
use TicketService\Generated\VenuesClient;

class VenuesApiClient {

    private $url;
    private $port;
    
    /**
     * @var \TicketService\Generated\VenuesIf
     */
    private static $_instance;

    private function __construct($url, $port) {
        $this->url = $url;
        $this->port = $port;
    }
    
    /**
     * @return \TicketService\Generated\VenuesIf venueService
     */
    public static function getInstance($url = null, $port = null) {
    	if (self::$_instance == null) {
    		return self::$_instance = new VenuesApiClient($url, $port);
    	}
    	return self::$_instance;
    }
    
    public function __call($method, $args) {
    	$transport = $this->getTransport();
    	$client = $this->getClient($transport);
    	
    	if(method_exists($client,$method)) {
    		$transport = $this->getTransport();
    		$return = call_user_func_array(array($client, $method), $args);
    		$transport->close();
    		return $return;
    	}
    }

    protected function getTransport() {
        $socket = new TSocket($this->url, $this->port);
        $socket->setSendTimeout(10000);
        $socket->setRecvTimeout(20000);
        $transport = new TBufferedTransport($socket);
        return $transport;
    }

    protected function getClient(TTransport $transport) {
        $protocol = new TBinaryProtocol($transport);
        $client = new \TicketService\Generated\VenuesClient($protocol);
        $transport->open();
        return $client;
    }

}