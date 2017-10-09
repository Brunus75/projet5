<?php

namespace NaoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NaoBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
