<?php namespace Keios\TransferToTools\API\ApiCommands;

use Keios\TransferToTools\API\ApiCommand;
use Keios\TransferToTools\API\Contracts\ApiCommandInterface;

// Allows to check current reseller balance

class CheckLimitApiCommand extends ApiCommand implements ApiCommandInterface
{
    protected $method = 'POST';

    protected $requiredArguments = [];

    public function getBody()
    {
      //$login = 'ResellerLogin';
      $key = $this->apiKey->getKey();
      $hash = $this->apiKey->getHash();

      return <<<XML
          <xml>
            <login>$login</login>
            <key>$key</key>
            <md5>$hash</md5>
            <action>ping</action>
          </xml>
XML;
    }
} 