<?php

namespace BBDO\Cms\Helpers;

use BBDO\Cms\Domain;

class Input
{

    /**
     * @param $config
     * @param $type
     * @param $model
     * @param null $multiple_index
     * @param string $block_type
     * @param string $index
     * @return array
     */
    public static function inputArray($config, $type, $model, $multiple_index = null, $block_type = '', $index = '')
    {
        $fieldgroup = $type == 'block' ? 'block_content[' . $block_type . '_' . $index . '][content]' : 'my_content';
        $cfg_type = $config['type'];
        $title = $config['title'];
        $options = array_key_exists('options', $config) ? $config['options'] : null;
        $editor = array_key_exists('editor', $config) ? $config['editor'] : null;
        $id = array_key_exists('id', $config) ? $config['id'] : null;
        $amount = array_key_exists('amount', $config) ? $config['amount'] : 0;
        $field = $fieldgroup . '[' . $cfg_type . ']';
        $asset_field_name = str_replace('[', '_', str_replace(']', '_', $fieldgroup)) . '_' . $cfg_type;
        $field_index = substr($cfg_type, strrpos($cfg_type, '_'));

        $input_type = $cfg_type;
        if ($multiple_index !== null) {
            $field = str_replace($cfg_type, $cfg_type . '_' . $multiple_index, $field);
            $asset_field_name = str_replace($cfg_type, $cfg_type . '_' . $multiple_index, $asset_field_name);

            $cfg_type = $cfg_type . '_' . $multiple_index;
        }

        $has_value = false;
        $content = null;
        if ($type == 'block') {
            if ($model != null) {
                $has_value = $model->block_content != null && array_key_exists($block_type . '_' . $index, $model->block_content)
                    && array_key_exists($cfg_type, $model->block_content[$block_type . '_' . $index]['content'])
                    && $model->block_content[$block_type . '_' . $index]['content'][$cfg_type] != '';
                $content = $model->block_content[$block_type . '_' . $index]['content'];
            }
        } else {
            $has_value = $model->my_content != null && $model->my_content->keys()->contains($cfg_type) && $model->my_content[$cfg_type] != '';
            $content = $model->my_content;
        }
        return [
            'id' => $id,
            'title' => $title,
            'type' => $cfg_type,
            'input_type' => $input_type,
            'field' => $field,
            'asset_field_name' => $asset_field_name,
            'editor' => $editor,
            'has_value' => $has_value,
            'content' => $content,
            'options' => $options,
            'field_index' => $index,
            'amount' => $amount,
            'multiple_fields' => $multiple_index !== null,
            'multiple_index' => $multiple_index,
        ];
    }

    /**
     * @param $module
     * @param $item
     * @param $lang
     * @param null $block_type
     * @param int $version
     * @param int $index
     * @return array
     */
    public static function linksArray($module, $item, $lang, $block_type = null, $version = 1, $index = 0)
    {

        $configKey = strtoupper($module);
        if ($block_type != null) {
            $configKey = strtoupper($module) . '.blocks.' . $block_type;
        }

        $itemService = new Domain\Item($module);
        $links = [];
        if (config("cms.$configKey.links") != null) {

            foreach (config("cms.$configKey.links") as $key => $link_cfg) {
                $type = $key;
                if (array_key_exists('fake_type_for', $link_cfg) && $link_cfg['fake_type_for'] != null) {
                    $type = $link_cfg['fake_type_for'];
                }
                if (array_key_exists('link_inception', $link_cfg) && $link_cfg['link_inception'] === true) {
                    $items = $itemService->getAllAdminListInception($type, $item->id, $key);
                } else {
                    $items = $itemService->getAllAdminList($type, $item->id, $key);
                }
                $field = 'linked_items_' . $key;
                if ($block_type != null) {
                    $block = $item->block($lang, $block_type . '_' . $index, $version);
                    $items = $itemService->getAllAdminListBlockLinks($type, $block != null ? $block->id : null, $lang, $key, $version);

                    $field = 'block_content[' . $block_type . '_' . $index . '][links]' . '[' . $key . ']';
                }
                $asset_field_name = str_replace('[', '_', str_replace(']', '_', $field));
                $links[$key] = array(
                    'description' => array_key_exists('description', $link_cfg) ? $link_cfg['description'] : null,
                    'items' => $items,
                    'type' => $link_cfg['type'],
                    'input_type' => $link_cfg['input_type'],
                    'add_item' => $link_cfg['add_item'],
                    'group_field' => $field . '[]',
                    'field' => $field,
                    'asset_field_name' => $asset_field_name
                );
            }
        }
        return $links;
    }

    /**
     * @param $input
     * @return bool|string
     */
    public static function formatBlockType($input)
    {
        return substr($input, 0, strripos($input, '_'));
    }

    /**
     * @param $input
     * @return bool|string
     */
    public static function indexBlockType($input)
    {
        return substr($input, strripos($input, '_') + 1);
    }
}