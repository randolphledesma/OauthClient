<?php
class NonceGenerator
{
    /**
     * This method generates and returns a unique nonce value
     * @return string A random 8 byte sequence encoded as a hex string (thus 16 chars long)
     */
    public static function generate()
    {
        return substr(md5(uniqid('nonce_', true)), 0, 16);
    }
}
