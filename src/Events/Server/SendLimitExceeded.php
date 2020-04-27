<?php

declare(strict_types=1);

namespace Postal\Events\Server;

class SendLimitExceeded extends SendLimit
{  
    const TYPE = 'SendLimitExceeded';
}
