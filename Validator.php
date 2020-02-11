<?php
  
namespace DSimageStorage;
require __DIR__ . '/Core.php';

class Validator extends Core
{
    
    public function validate($data, $type, $index=null)
    {
        if ($type == "string")
        {
            if (is_string($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not string.'];
                return ['fail' => 'Validator: type is not string.'];
                
            }  
        }
        else if (strpos($type, "string:") === 0)
        {
            
            $typeExpl = explode(':', $type);
            
            if (count($typeExpl) == 2)
            {
                            
                if (ctype_digit($typeExpl[1]))
                {           
                          
                    if ((strlen($data) <= intval($typeExpl[1])))
                    {                        
                        return ['success' => null];                        
                    }
                    else
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                        return ['fail' => 'Validator: string  overflow.'];
                    }
                }                
                else 
                {
                    if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type must be in this format "string:123".'];
                    return ['fail' => 'Validator: type must be in this format "string:123".'];
                }
            }
            else if (count($typeExpl) == 3) //string:12:alpha
            {
                if ($typeExpl[2] == 'alpha')
                {
                    if (ctype_alpha($data))
                    {         
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }                            
                                           
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not alphabetic.'];
                        return ['fail' => 'Validator: string is not alphabetic.'];
                    }
                }                
                else if ($typeExpl[2] == 'alnum')
                {
                    if (ctype_alnum($data))
                    {       
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }  
                        
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not alphanumeric.'];
                        return ['fail' => 'Validator: string is not alphanumeric.'];
                    }
                }
                else if ($typeExpl[2] == 'digit')
                {
                    if (ctype_digit($data))
                    {      
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                             
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not numeric.'];
                        return ['fail' => 'Validator: string is not numeric.'];
                    }
                }
                else if ($typeExpl[2] == 'hex')
                {
                    if (ctype_xdigit($data))
                    {       
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                            
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not hexdecimal.'];
                        return ['fail' => 'Validator: string is not hexdecimal.'];
                    }
                }
                else if ($typeExpl[2] == 'under')
                {
                    if (preg_match("/^[A-Za-z0-9_]+$/", $data) )
                    {       
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                            
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not alphanumeric with underscore.'];
                        return ['fail' => 'Validator: string is not alphanumeric with underscore.'];
                    }
                }
                else if ($typeExpl[2] == 'email')
                {                        
                    if (filter_var($data, FILTER_VALIDATE_EMAIL))
                    {       
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                            
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not valid email.'];
                        return ['fail' => 'Validator: string is not valid email.'];
                    }
                }
                else if ($typeExpl[2] == 'url')
                {                        
                    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $data)) 
                    {       
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                            
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not valid url.'];
                        return ['fail' => 'Validator: string is not valid url.'];
                    }
                }
                else if ($typeExpl[2] == 'json')
                {                        
                    $rgx = '/
                      (?(DEFINE)
                         (?<number>   -? (?= [1-9]|0(?!\d) ) \d+ (\.\d+)? ([eE] [+-]? \d+)? )
                         (?<boolean>   true | false | null )
                         (?<string>    " ([^"\n\r\t\\\\]* | \\\\ ["\\\\bfnrt\/] | \\\\ u [0-9a-f]{4} )* " )
                         (?<array>     \[  (?:  (?&json)  (?: , (?&json)  )*  )?  \s* \] )
                         (?<pair>      \s* (?&string) \s* : (?&json)  )
                         (?<object>    \{  (?:  (?&pair)  (?: , (?&pair)  )*  )?  \s* \} )
                         (?<json>   \s* (?: (?&number) | (?&boolean) | (?&string) | (?&array) | (?&object) ) \s* )
                      )
                      \A (?&json) \Z
                      /six';
                    
                    if (preg_match($rgx, $data))
                    {
                        if (strlen($data) > intval($typeExpl[1]))
                        {
                            if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string overflow.'];
                            return ['fail' => 'Validator: string overflow.'];     
                        }   
                                            
                        return ['success' => null];  
                    }
                    else 
                    {
                        if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' string is not valid json.'];
                        return ['fail' => 'Validator: string is not valid json.'];
                    }  
                }

                
            }
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' bad string argument format.'];
                return ['fail' => 'Validator: bad string argument format.'];    
            }
        }            
 
        else if ($type == 'array')
        {
            if (is_array($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not array.'];
                return ['fail' => 'Validator: type is not array.'];
            }       
        }
        else if ($type == 'int')
        {
            if (is_int($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not integer.'];
                return ['fail' => 'Validator: type is not integer.'];
            }       
        }
        else if ($type == 'float')
        {
            if (is_float($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not float.'];
                return ['fail' => 'Validator: type is not float.'];
            }       
        }
        else if ($type == 'object')
        {
            if (is_object($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not object.'];
                return ['fail' => 'Validator: type is not object.'];
            }       
        }
        else if ($type == 'bool')
        {
            if (is_bool($data))
            {
                return ['success' => $index];
            } 
            else
            {
                if (isset($index)) return ['fail' => 'Validator: argument number: '. $index .' type is not bool.'];
                return ['fail' => 'Validator: type is not bool.'];
            }       
        }
        else 
        {
            return ['fail' => 'Validator: arguments are: int, array, float, object, string.'];
        }
        

    }
  
    public function validateArgs($data, $types)
    {
        if (!is_array($data) || !is_array($types))
        {
            return ['fail' => 'Validator: argument must be type: array.'];
        }

        if (count($data) != count($types))
        {
            return ['fail' => 'Validator: Argument arrays must have same number of members.'];
        }

        $msg = null;

        for ($i=0; $i<count($data); $i++)
        {
            $msg = $this->validate($data[$i], $types[$i], $i);
            if (isset($msg['fail'])){
                return $msg;
            }            
        }

        return ['success' => 'Last msg'];
    }
    
    
};
