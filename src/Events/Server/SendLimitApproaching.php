<?php

declare(strict_types=1);

namespace Postal\Events\Server;

class SendLimitApproaching extends SendLimit
{  
    const TYPE = 'SendLimitApproaching';
}
