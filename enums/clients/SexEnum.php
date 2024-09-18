<?php

namespace app\enum\clients;

class SexEnum
{
    const MASCULINE = 'm';
    const FEMININE = 'f';


    /**
     * Returns a list of gender options
     *
     * @return array
     */
    public static function getOptions()
    {
        return [
            self::MASCULINE => 'M',
            self::FEMININE => 'F',
        ];
    }
}
