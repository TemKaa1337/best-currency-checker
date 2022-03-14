<?php

namespace App\Services\Traits\Parser;

use App\Models\Department;

trait Helper
{
    protected function splitPhones(string $phones): array
    {
        return array_map(fn (string $elem): string => trim($elem), preg_split('/(;|,)/', $phones));
    }

    protected function trimLastUpdate(string $lastUpdate): string
    {
        $lastUpdate = trim($lastUpdate);

        if (mb_strlen($lastUpdate) > 5) {
            $lastUpdate = preg_replace('!\s+!', ' ', $lastUpdate);
            $dateTimeInfo = explode(' ', $lastUpdate);
            array_pop($dateTimeInfo);

            $lastUpdate = implode(' ', [$dateTimeInfo[0], $dateTimeInfo[1]]);
        }

        return $lastUpdate;
    }

    protected function update(array $update): void
    {
        foreach ($update as $department) {
            $currentDepartment = Department::where([
                ['name', $department['name']],
                ['coordinates', $department['coordinates']],
                ['bank_name', $department['bank_name']]
            ])->get()->first();

            if ($currentDepartment === null) {
                Department::insert($department);
            } else {
                $currentDepartment->fill($department);
                $currentDepartment->save();
            }
        }

        echo 'updated successfully.'.PHP_EOL;
    }
}

?>
