<?php

declare(strict_types=1);

namespace App\Traits;

use OpenApi\Attributes as SWG;
use Symfony\Component\Serializer\Annotation\Groups;

trait ResultTrait
{
    #[SWG\Property(description: 'Response result', type: 'boolean', example: true)]
    #[Groups(['swagger'])]
    public bool $result = true;
}
