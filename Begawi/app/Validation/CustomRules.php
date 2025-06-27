<?php

namespace App\Validation;

use CodeIgniter\I18n\Time;

class CustomRules
{
    public function is_future_date(?string $str = null, ?string &$error = null): bool
    {
        if (empty($str)) {
            return true;
        }
        try {
            return Time::parse($str, 'Asia/Makassar')->isAfter(Time::now('Asia/Makassar'));
        } catch (\Exception $e) {
            $error = 'Format tanggal tidak valid.';
            return false;
        }
    }

    public function after_field(?string $str, string $field, array $data, ?string &$error = null): bool
    {
        if (empty($str) || empty($data[$field])) {
            return true;
        }

        try {
            $firstDate = Time::parse($data[$field], 'Asia/Makassar');
            $secondDate = Time::parse($str, 'Asia/Makassar');

            if ($secondDate->isBefore($firstDate)) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            $error = "Format tanggal tidak valid.";
            return false;
        }
    }
}
