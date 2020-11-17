<?php


namespace App\Support\Entity;


use App\Constants\EditStatus;

trait EditStatusResolver
{
    public function resolveEditStatus()
    {
        return EditStatus::MODIFIED;
    }
}
