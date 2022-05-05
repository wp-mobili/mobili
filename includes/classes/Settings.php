<?php


namespace Mobili;


class Settings
{
    /**
     * html input renderer
     *
     * @param array $args
     * @param bool $return
     *
     * @return false|string
     * @since 1.0.0
     */
    public static function input(array $args = [], bool $return = false)
    {
        $args = array_merge(
            [
                'name' => '',
                'id' => '',
                'type' => 'text',
                'value' => '',
                'desc' => '',
                'placeholder' => '',
                'class' => [],
                'required' => false,
                'attributes' => ''
            ], $args
        );
        if (!in_array($args['type'], ['text', 'number', 'search', 'email', 'password'])) {
            $args['type'] = 'text';
        }
        if (empty($args['id'])) {
            $args['id'] = $args['name'];
        }
        if (empty($args['class'])) {
            $args['class'] = ['regular-text'];
        }
        if (is_string($args['class'])) {
            $args['class'] = [$args['class']];
        }
        if (empty($args['value']) && !empty($args['name'])) {
            $args['value'] = get_option($args['name'], '');
        }

        if ($args['required']) {
            $args['attributes'] .= ' required';
        }

        $args['attributes'] = str_replace(['[value]'], [$args['value']], $args['attributes']);

        if ($return) {
            ob_start();
        }
        echo sprintf(
            '<input type="%s" name="%s" id="%s" class="%s" placeholder="%s" value="%s" %s/>', esc_attr($args['type']),
            esc_attr($args['name']), esc_attr($args['id']), esc_attr(implode(' ', $args['class'])), esc_attr($args['placeholder']), esc_attr($args['value']),
            $args['attributes']
        );
        if (!empty($args['desc'])) {
            echo sprintf('<p class="description">%s</p>', esc_html($args['desc']));
        }
        if ($return) {
            return ob_get_clean();
        }
    }

    /**
     * html textarea renderer
     *
     * @param array $args
     *
     * @since 1.0.0
     */
    public static function textarea(array $args = [])
    {
        $args = array_merge(
            [
                'name' => '',
                'id' => '',
                'value' => '',
                'desc' => '',
                'placeholder' => '',
                'row' => '6',
                'class' => [],
            ], $args
        );
        if (empty($args['id'])) {
            $args['id'] = $args['name'];
        }
        if (empty($args['class'])) {
            $args['class'] = ['regular-text'];
        }
        if (is_string($args['class'])) {
            $args['class'] = [$args['class']];
        }
        if (empty($args['value']) && !empty($args['name'])) {
            $args['value'] = get_option($args['name'], '');
        }

        echo sprintf(
            '<textarea name="%s" id="%s" class="%s" placeholder="%s" rows="%s">%s</textarea>', esc_attr($args['name']),
            esc_attr($args['id']), esc_attr(implode(' ', $args['class'])), esc_attr($args['placeholder']), esc_attr($args['row']), esc_textarea($args['value'])
        );
        if (!empty($args['desc'])) {
            echo sprintf('<p class="description">%s</p>', esc_html($args['desc']));
        }
    }

    /**
     * html checkbox renderer
     *
     * @param array $args
     *
     * @since 1.0.0
     */
    public static function checkbox(array $args = [])
    {
        $args = array_merge(
            [
                'name' => '',
                'id' => '',
                'label' => __('Active', 'mobili'),
                'value' => false,
                'desc' => '',
                'class' => [],
            ], $args
        );
        if (empty($args['id'])) {
            $args['id'] = $args['name'];
        }
        if (empty($args['class'])) {
            $args['class'] = ['regular-text'];
        }
        if (is_string($args['class'])) {
            $args['class'] = [$args['class']];
        }
        if (empty($args['value']) && !empty($args['name'])) {
            $args['value'] = get_option($args['name'], '');
            $args['value'] = $args['value'] === 'on' ? 1 : 0;
        }

        echo sprintf(
            '<label><input type="checkbox" name="%s" id="%s" class="%s" ' . checked($args['value'], true, false) . '/>%s</label>',
            esc_attr($args['name']), esc_attr($args['id']), esc_attr(implode(' ', $args['class'])), esc_html($args['label'])
        );
        if (!empty($args['desc'])) {
            echo sprintf('<p class="description">%s</p>', esc_html($args['desc']));
        }
    }

    /**
     * html media input renderer
     *
     * @param array $args
     *
     * @since 1.0.0
     */
    public static function imageUpload(array $args = [])
    {
        $args = array_merge(
            [
                'desc' => '',
                'select' => '',
                'class' => [],
            ], $args
        );

        $inputRender = self::input(
            array_merge(
                $args, [
                    'class' => array_merge($args['class'], ['regular-text', 'media-input']),
                    'desc' => '',
                    'type' => 'text'
                ]
            ), true
        );

        if (empty($args['select'])) {
            $args['select'] = __('Select', 'mobili');
        }

        echo sprintf(
            '<div class="mi-media-input">%s<button class="button select-media" type="button">%s</button></div>',
            esc_html($inputRender), esc_html($args['select'])
        );
        if (!empty($args['desc'])) {
            echo sprintf('<p class="description">%s</p>', esc_html($args['desc']));
        }
    }

    /**
     * html color input renderer
     *
     * @param array $args
     *
     * @since 1.0.0
     */
    public static function colorPicker(array $args = [])
    {
        $args = array_merge(
            [
                'desc' => '',
                'select' => '',
                'class' => [],
            ], $args
        );

        self::input(
            array_merge(
                $args, [
                    'class' => array_merge($args['class'], ['regular-text', 'mi-color-input']),
                    'type' => 'text'
                ]
            )
        );
    }


    /**
     * html more options renderer
     *
     * @param array $args
     *
     * @since 1.0.0
     */

    public static function moreOptions(array $args)
    {
        echo sprintf('<div class="mi-show-more-options">%s</div>', __('Show more options...', 'mobili'));
    }
}