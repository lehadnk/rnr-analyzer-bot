<?php
/**
 * BestAttempt.php
 * Creator: lehadnk
 * Date: 12/09/2017
 */

namespace App\Conversations;




class Math extends ConversationBase
{
    public function giveAnswer()
    {
        $expression = $this->getDataFieldValue('expression');

        try {
            $result = eval('return '.$expression.';');
        } catch (\Exception $e) {
            return 'У тебя выражение кривое.';
        }

        return $result.', глупенький!' ;
    }
}