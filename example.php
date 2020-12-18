<?php

    $secret = "96CC59D4F8D6131D816D251AFDD12";

    $exp = 24*60*60*1000; // 24 hours in milli seconds

    $claims = ["sub"=>"1234567890", "name"=>"John Doe"];

    require_once("jwt_class.php");

    $jwt_class = new JWT_class();
    
    $jwt_res = $jwt_class->generateJWT($claims, $secret, $exp); // Default HS256

    echo $jwt_res;
    
    
    /*
        Response: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNjA4MjczNTExMjE4LjI3NjksImV4cCI6MTYwODM1OTkxMTIxOC4yODg4fQ.LXb9nK7BjZ-DMBrGdyqcqe81Yq53S8o8mqZ7JDGaKxY

        Header:
                {
                    "alg": "HS256",
                    "typ": "JWT"
                }
        
        Payload:
                {
                    "sub": "1234567890",
                    "name": "John Doe",
                    "iat": 1608273511218.2769,
                    "exp": 1608359911218.2888
                }

        Note:
                'iat' claim added explicitly
                'exp' claim is the sum of 'iat' and '$exp' from parameter

    */

    $validation_res = $jwt_class->validateJWT($jwt_res, $secret);

    /*
        Note: if token is not expired and validated then it would return payload's data
              else it would return a response with an error message
    */


?>
