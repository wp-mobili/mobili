<?php

/**
 * check pwa status is active
 *
 * @return bool
 * @since 1.0.0
 */
function mi_pwa_is_active() : bool {
	return get_option('mobili-pwa_status', 'on') === 'on';
}

/**
 * check pwa desktop status is active
 *
 * @return bool
 * @since 1.0.0
 */
function mi_pwa_desktop_is_active() : bool {
	return get_option('mobili-pwa_desktop', 'on') === 'on';
}