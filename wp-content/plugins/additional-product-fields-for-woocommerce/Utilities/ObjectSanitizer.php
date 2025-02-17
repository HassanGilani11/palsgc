<?php


namespace rednaowooextraproduct\Utilities;


class ObjectSanitizer
{
    public static function Sanitize($obj,$dictionary)
    {
        return (new ObjectSanitizer())->SanitizeProperty($obj,$dictionary);
    }

    public function SanitizeProperty($obj,$dictionaryValue)
    {
        if(is_array($dictionaryValue)&&!$this->IsAssoc($dictionaryValue))
        {
            return $this->SanitizeArray($obj,$dictionaryValue);
        }

        if(is_array($dictionaryValue)&&$this->IsAssoc($dictionaryValue))
        {
            return $this->SanitizeObject($obj,$dictionaryValue);
        }


        if(is_object($dictionaryValue))
            return $this->SanitizeUnknownObject($obj,$dictionaryValue);


        if(is_string($dictionaryValue))
        {
            return Sanitizer::SanitizeString($obj);
        }

        if(is_numeric($dictionaryValue))
        {
            return Sanitizer::SanitizeNumber($obj);
        }

        if(is_numeric($dictionaryValue))
        {
            return Sanitizer::SanitizeNumber($obj);
        }

        if(is_bool($dictionaryValue))
        {
            return Sanitizer::SanitizeBoolean($obj);
        }

        if($dictionaryValue==null)
            return $obj;

        return $obj;
    }

    private function SanitizeArray($obj, $dictionary)
    {
        if(count($dictionary)==0)
            return $obj;

        $arrayType=$dictionary[0];
        if(!is_array($obj))
        {
            $obj=[];
        }

        $arrayToReturn=[];
        foreach($obj as $item)
        {
            $arrayToReturn[]=$this->SanitizeProperty($item,$arrayType);
        }

        return $arrayToReturn;


    }

    function IsAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    private function SanitizeObject($obj, $dictionary)
    {
        if($obj==null)
            $obj=new \stdClass();

        if(is_array($obj))
            $obj=(object)$obj;


        foreach($dictionary as $key=>$value)
        {
            $tosanitizevalue=null;
            if(is_object($obj)&&isset($obj->$key))
            {
                $tosanitizevalue=$obj->$key;
            }

            $obj->$key=$this->SanitizeProperty($tosanitizevalue,$value);


        }

        return $obj;

    }

    private function SanitizeUnknownObject($obj, $dictionaryValue)
    {
        if($obj==null)
            return null;

        if(is_object($obj))
            return $obj;

        $obj=json_decode($obj);
        if($obj===false)
            return null;
        return $obj;
    }
}