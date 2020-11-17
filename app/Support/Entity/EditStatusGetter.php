<?php


namespace App\Support\Entity;


use App\Constants\EditStatus;

trait EditStatusGetter
{
    protected function getEditStatus()
    {
        $default = EditStatus::MODIFIED;

        if (array_key_exists('editStatus', $this->getData())) {
            $editStatus = $this->getData()['editStatus'];
            if (empty($editStatus)) {
                return $default;
            } else {
                return $editStatus;
            }
        } else {
            return $default;
        }
    }
}
