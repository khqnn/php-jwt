# php-jwt [![Generic badge](https://img.shields.io/badge/version-v1.0-green.svg)](https://shields.io/)

PHP implementation of Json Web Token based on RFC standards.

Supported Algorithms
1.  HS256 (Default)
2.  HS384
3.  HS512

# validation responses
While validating a Json Web Token with secret key then returned response might be one of these

{"message": "", "status": 200, "result": "data"}

{"message": "no authorization token", "status": 401}

{"message": "invalid token", "status": 401}

{"message": "token expired", "status": 401}

{"message": "unauthorized request", "status": 401}


# exp check: enabled
