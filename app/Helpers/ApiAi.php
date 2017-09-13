<?php
/**
 * ApiAi.php
 * Creator: lehadnk
 * Date: 13/09/2017
 */

namespace App\Helpers;


use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class ApiAi extends \BotMan\BotMan\Middleware\ApiAi
{
    /**
     * Perform the API.ai API call and cache it for the message.
     * @param  \BotMan\BotMan\Messages\Incoming\IncomingMessage $message
     * @return stdClass
     */
    protected function getResponse(IncomingMessage $message)
    {
        if (substr($message->getText(), 0, 1) == '<') {
            $msg = substr($message->getText(), 22);
        }

        $response = $this->http->post($this->apiUrl, [], [
            'query' => [$msg],
            'sessionId' => md5($message->getRecipient()),
            'lang' => 'en',
        ], [
            'Authorization: Bearer '.$this->token,
            'Content-Type: application/json; charset=utf-8',
        ], true);

        $this->response = json_decode($response->getContent());

        return $this->response;
    }
}