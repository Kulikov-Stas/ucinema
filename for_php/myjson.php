<?php

class myjson
{
    private $response;
    
    function __construct() 
    {
        $this->response = array('state'=>0, 'data'=>'');
    }
    
    public function forarray($data)
    {
        if(!empty($data))
        {
            foreach ($data as $key => $value)
            {
                if(is_array($data[$key]))
                {
                    $data[$key] = $this->forarray($data[$key]);
                }
                else
                {
                    $data[$key] = iconv('cp1251', 'UTF-8', $value);
                }
            }
        }
        return $data;
    }
    
    public function fill($data)
    {
        if(!empty($data))
        {
            $this->response['state'] = 1;
            foreach ($data as $key => $value)
            {
                $data[$key] = iconv('cp1251', 'UTF-8', $value);
            }
            $this->response['data'] = $data;
        }
        return json_encode($this->response);
    }
}
?>
