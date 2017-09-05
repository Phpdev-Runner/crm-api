<?php
/**
 * Created by PhpStorm.
 * User: Work
 * Date: 05.09.2017
 * Time: 12:12
 */

namespace App\Http\Requests;

use App\Domain;


trait DomainDuplicateTrait
{
    private function checkDomainDuplicates(array $domains, $id = false)
    {
        $duplicatesDomains = Domain::checkDomainDuplicates($domains, $id);

        return $duplicatesDomains;
    }
}