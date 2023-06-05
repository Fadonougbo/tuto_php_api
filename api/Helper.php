<?php
namespace API;

class Helper
{

    public static function selectValidKeys(array $currentArray,array $valideArray,?bool $useKey=false):array|false
    {
        $filter=$useKey?ARRAY_FILTER_USE_KEY:ARRAY_FILTER_USE_BOTH;

        $elements=$currentArray??[];

        $finaleArr=array_filter($elements,function($el) use($valideArray)
        {
            return in_array($el,$valideArray);
        },$filter);

        return !empty($finaleArr)?$finaleArr:false;
    }

    public static function generateUpdateSetQuery(array $columnList):string
    {
        $x=array_map(function($el)
        {
            return "$el=:$el";

        },$columnList);

        return implode(",",$x);
    } 
}

?>