<?php
/**
 * check is ios device
 *
 * @return bool
 */
function mi_is_ios(): bool
{
    return wp_is_mobile() && preg_match( '/iPad|iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] );
}