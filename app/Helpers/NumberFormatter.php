<?php

namespace App\Helpers;

class NumberFormatter
{
    public static function formatIndianCurrency($num)
    {
        $explrestunits = "";
        if (strlen($num) > 3) {
            $lastthree = substr($num, -3);
            $restunits = substr($num, 0, -3); // extracts the last three digits
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for ($i = 0; $i < sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if ($i == 0) {
                    $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $FormatedNum = $explrestunits . $lastthree;
        } else {
            $FormatedNum = $num;
        }
        return $FormatedNum;
    }

    public static function formatIndianNumber($num, $precision = 2)
    {
        // Separate integer and decimal parts
        $parts = explode('.', $num);
        $integerPart = $parts[0];
        $decimalPart = isset($parts[1]) ? substr($parts[1], 0, $precision) : '';

        $formattedInteger = self::formatIndianCurrency($integerPart);

        if (!empty($decimalPart)) {
            return $formattedInteger . '.' . $decimalPart;
        }

        return $formattedInteger;
    }
}