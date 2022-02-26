<?php
/**
 * check is mobile device
 *
 * @return bool
 */
function mi_is_mobile(): bool{
    $mobileDetect= new Mobile_Detect();
    return $mobileDetect->isMobile();
}
/**
 * check is tablet device
 *
 * @return bool
 */
function mi_is_tablet(): bool{
    $mobileDetect= new Mobile_Detect();
    return $mobileDetect->isTablet();
}

/**
 * check is watch device
 *
 * @return bool
 */
function mi_is_watch(): bool{
    $mobileDetect= new Mobile_Detect();
    return $mobileDetect->isWatch();
}

/**
 * check is android os
 *
 * @return bool
 */
function mi_is_android(): bool{
    $mobileDetect= new Mobile_Detect();
    return $mobileDetect->isAndroidOS();
}
/**
 * check is ios device
 *
 * @return bool
 */
function mi_is_ios(): bool
{
    return wp_is_mobile() && preg_match( '/iPad|iPod|iPhone/', $_SERVER['HTTP_USER_AGENT'] );
}