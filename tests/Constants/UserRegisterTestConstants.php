<?php

namespace Tests\Constants;

class UserRegisterTestConstants
{
    public const VALID_DISPLAY_NAME = 'John Doe';

    public const VALID_EMAIL = 'john.doe@gmail.com';

    public const VALID_PHONE_NUMBER = '9876543210';

    public const VALID_PASSWORD = 'Password123';

    public const VALID_USERNAME = 'johndoe';

    public const INVALID_PHONE_NUMBER = '123456789';

    public const INVALID_EMAIL = 'john.doe@example';

    public const INVALID_PASSWORD = 'pass';

    public const REGISTER_USER_ENDPOINT = '/api/v1/auth/register';
}
