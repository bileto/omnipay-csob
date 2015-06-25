<?php

namespace Omnipay\Csob\Sign;

class Preparer
{
    /**
     * @param array $data
     * @param array $arrayKeys
     * @return string
     */
    public function getStringToSign(array $data, array $arrayKeys)
    {
        $str = '';
        foreach ($arrayKeys as $key) {
            if (!isset($data[$key]) || $data[$key] === null) {
                continue;
            }
            $value = $data[$key];
            if ($value === true) {
                $str .= 'true';
            } elseif ($value === false) {
                $str .= 'false';
            } elseif (is_array($value)) {
                $str .= $this->getStringToSign($value, array_keys($value));
            } else {
                $str .= (string)$data[$key];
            }
            $str .= '|';
        }
        return rtrim($str, '|');
    }
}