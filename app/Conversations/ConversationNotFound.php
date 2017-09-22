<?php
/**
 * ConversationNotFound.php
 * Creator: lehadnk
 * Date: 22/09/2017
 */

namespace App\Conversations;


class ConversationNotFound extends ConversationBase
{
    public function giveAnswer() {
        return 'Не знаю о чем ты.';
    }
}