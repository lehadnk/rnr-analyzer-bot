<?php
/**
 * Factory.php
 * Creator: lehadnk
 * Date: 22/09/2017
 */

namespace App\Conversations;


class Factory
{
    public static function factory($intention) {
        $className = 'App\\Conversations\\'.str_replace('-', '', ucfirst($intention));

        if (class_exists($className)) {
            return new $className;
        }

        return new ConversationNotFound();
    }
}