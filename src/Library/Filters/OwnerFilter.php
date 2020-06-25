<?php

namespace App\Library\Filters;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class OwnerFilter extends SQLFilter
{
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if ($targetEntity->hasAssociation('owner') && $this->hasParameter('owner')) {
            return '(' . $targetTableAlias . '.owner_id = ' . $this->getParameter('owner') . ')';
        }

        return '';
    }
}