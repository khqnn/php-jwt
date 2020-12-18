<?php
class JWT_class {


   public function __construct()
    {

        /*
            Currently this version of JWT library supports following hash algorithms
            1.  HS256 (default)
            2.  HS384
            3.  HS512
            
            Note: HS256 is default algorithm. If algorithm is not given or an unsupported algorithm is provided
            then this algorithm would be used as hashing algorithm
        */
    }


    private function getHmacAlgo($alg='HS256'){
        switch ($alg) {
            case 'HS384':
                return ['alg'=>$alg, 'hmac'=>'sha384'];
            case 'HS512':
                return ['alg'=>$alg, 'hmac'=>'sha512'];
            default:
                return ['alg'=>'HS256', 'hmac'=>'sha256'];
            
        }


    }

    public function validateJWT($token='', $key=''){
        /*
            params: 
                token:  a jwt token based on RFC standard
                key:    key to validate jwt signature. it must be the same key that were used to create jwt

            returns:
                if token is validated successfully then payload would be returned as an array
                if token is not provided, expiry is not provided, expired or couldn't be able to verify signature
                then it would return an array with status code 401 and a relevent error message as string

            note:
                exp is the expiry of jwt. it must be in jwt payload to confirm token validity 
                otherwise token would not be validated
        
        */


        if($token=='')
            return array('message'=>'no authorization token', 'status'=>401);
        $seg = explode('.', $token);
        $header = json_decode(base64_decode($seg[0]));
        $payload = json_decode(base64_decode($seg[1]));
    
        $alg = $this->getHmacAlgo($header->alg);

        if(!isset($payload->exp) ) // if exp not attached with token
            return array('message'=>'invalid token', 'status'=>401);
        elseif($payload->exp<$this->current_time_millis()) // if expiry has been passed
            return array('message'=>'token expired', 'status'=>401);
    
        // here we know token is still active
        $hash = $this->base64UrlEncode(hash_hmac($alg['hmac'], $seg[0].'.'.$seg[1], $key, true));
        if ($hash==$seg[2]) 
            return array('message'=>"", "status"=>200,"result"=>$payload);
        else
            return array('message'=>'unauthorized request', 'status'=>401);
    
       
        
    }

    public function generateJWT($data=array(), $key='', $exp=300000, $alg='HS256')
    {
        /*
            params:
                data => these are the claims and it will be the part of payload
                key  => secrete key to generate jwt signature
                alg  => hashing algorithm to generate and validate JWT signature
                exp  => is the time in milli seconds after that token would be expired. default is 5 mins(300000 ms)

            returns:
                An RFC standard base Json Web Token. 

            note: 
                in claims it would append token creation time and token expiry
                token expiry means after how much time token would be expired
                exp(milli seconds) would be added to current milli seconds time
                for example exp=300000 means token would expire after 5 mins of token creation
        */


        $data['iat'] = $this->current_time_millis();
        $data['exp'] = $this->current_time_millis($exp);
        
        return $this->getStructuredToken($alg, $data, $key);

    }

    public function generateJWTWithCustomExp($data=array(), $key='', $exp='', $alg='HS256')
    {
        /*
            params:
                data => these are the claims and it will be the part of payload
                key  => secrete key to generate jwt signature
                alg  => hashing algorithm to generate and validate JWT signature
                exp  => token expiry given as human readable string for example '2020-12-31'
            
            returns:
                An RFC standard base Json Web Token. 
            
            note: 
                this method is different from generateJWT in a way that it gets custome absolute exp as a string
                and convert this string into milli seconds
                whereas generateJWT takes exp in milli seconds and add it to current time in millis
        */

        $data['exp'] = strtotime($exp)*1000;
        return $this->getStructuredToken($alg, $data, $key);

    }


    private function getStructuredToken($alg, $data, $key){
        $alg = $this->getHmacAlgo($alg);
        $header = $this->base64UrlEncode(json_encode(["alg"=>$alg['alg'], "typ"=>"JWT"]));
        $payload = $this->base64UrlEncode(json_encode($data));
        $hash = $this->base64UrlEncode(hash_hmac($alg['hmac'], "$header.$payload" , $key, true));
        return "$header.$payload.$hash";
    }

    function base64UrlEncode($text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }

    function current_time_millis($ahead = 0){
        /*
            Note: this function gets a parameter as time in milli seconds and add this time into current
            time in milli seconds. For example for $ahead=0 the current time in ms would be retured and
            if $ahead=300000 would be given then this function would return 5 mins later time from current
            timestamp
        */

        return microtime(true)*1000+$ahead;
    }


}