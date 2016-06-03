<?php
/**
 * Created by PhpStorm.
 * User: Beni
 * Date: 13/07/2015
 * Time: 10:18 AM
 */

namespace App\AuthTraits;

trait RedirectsUsers
{
    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (property_exists($this, 'redirectPath')) {
            return $this->redirectPath;
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}