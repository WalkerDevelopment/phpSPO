<?php


namespace Office365\PHP\Client\Runtime\OData;

/**
 * Represents the OData raw query values in the string format from the incoming request.
 */
class ODataQueryOptions
{

    public function isEmpty()
    {
        return (count($this->getProperties()) == 0);
    }

    public function toUrl()
    {
        if ($this->more) {
            return $this->nextURL;
        }
        $url = implode('&', array_map(function ($key, $val) {
            $key = "\$" . strtolower($key);
            return "$key=$val";
        }, array_keys($this->getProperties()), $this->getProperties()));
        return $url;
    }

    public function checkNext($response)
    {
        $this->more = false;

        if (property_exists($response, 'd') && property_exists($response->d, '__next')) {
            $this->more = true;
            $this->nextURL = explode('?', $response->d->__next)[1];
        }
        // dd([$response, $this]);
    }


    private function getProperties()
    {
        return array_filter((array) $this);
    }

    public $Select;

    public $Filter;

    public $Expand;

    public $OrderBy;

    public $Top;

    public $Skip;

    public $Search;

    public $more = false;

    public $nextURL;
}
