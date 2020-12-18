## Intro

jwt-php is the light weight ready to use standard php class module which can be used in any php framework like Codeigniter, Laravel or in Core PHP. It is a stand alone module to generate and validate standard Json Web Token on servers running php scripts.

### Markdown

Easy to use. Generate and validate JW tokens in any php script.

Generate Json Web Tokens
```markdown
    $secret = "96CC59D4F8D6131D816D251AFDD12";
    $one_day = 86400000; // 24 hours in milli seconds
    $claims = ["sub"=>"1234567890", "name"=>"John Doe"];

    require_once("jwt_class.php");
    $jwt_class = new JWT_class();
    $jwt_res = $jwt_class->generateJWT($claims, $secret, $one_day); // Default HS256

```

Validate Json Web Tokens
```markdown
    $secret = "96CC59D4F8D6131D816D251AFDD12";
    $token = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNjA4MjczNTExMjE4LjI3NjksImV4cCI6MTYwODM1OTkxMTIxOC4yODg4fQ.LXb9nK7BjZ-DMBrGdyqcqe81Yq53S8o8mqZ7JDGaKxY";
    
    require_once("jwt_class.php");
    $jwt_class = new JWT_class();
    $jwt_res = $jwt_class->validateJWT($token, $secret);

```


### Validation Response

{"message": "", "status": 200, "result": "data"}

{"message": "no authorization token", "status": 401}

{"message": "invalid token", "status": 401}

{"message": "token expired", "status": 401}

{"message": "unauthorized request", "status": 401}

