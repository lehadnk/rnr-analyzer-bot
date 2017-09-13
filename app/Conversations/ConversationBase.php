<?php
/**
 * CoversationBase.php
 * Creator: lehadnk
 * Date: 12/09/2017
 */

namespace App\Conversations;


use BotMan\BotMan\Messages\Conversations\Conversation;

abstract class ConversationBase extends Conversation
{
    protected $data;

    public function setApiParameters($parameters) {
        $this->data = $parameters;
    }

    public function run() {
        $answer = $this->giveAnswer();
        return $this->say($answer);
    }

    abstract function giveAnswer();

    protected function getDataFieldValue($name) {
        if (!isset($this->data[$name])) {
            return null;
        }
        return $this->data[$name];
    }
}