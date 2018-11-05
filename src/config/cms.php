<?php
return array(
    'default_locale' => 'nl-BE',
    'default_cache_duration' => 60 * 24 * 30,
    // Modules used by asset managers to list the linked modules.
    'modules' => array('CASES', 'PRODUCTS', 'CATEGORIES', 'CATEGORIESTEST',),

    // List of all the content modules ( single page modules )
    'content_modules' => array(),

    'sentinel' => array(),
    'custom_views' => array(
        'links' => 'admin.partials.input.links',
        'form' => 'admin.partials.form',
    ),
    'files' => array(
        'image' => array(
            'acceptedFiles' => 'image/*',
            'maxFileSize' => 2,
            'content_type' => array(
                'image',
                'download',
            ),
        ),
        'file' => array(
            'acceptedFiles' => 'application/pdf',
            'maxFileSize' => 5,
            'content_type' => array(
                'download'
            ),
        ),
    ),
    'image_types' => [
        'image_default' => [
            'generate_thumb' => true,
            'thumb_width' => 300,
            'thumb_height' => null,
            'optimize_original' => false,
            'width' => 800,
            'height' => null,
        ],
    ],
    'user' => array(
        'description' => 'Users'
    ),
    'CASES' => array(
        'description' => 'Cases',
        'sortable' => true,
        'show_start_date' => false,
        'sort_by' => 'sort',
        'overview_custom' => false,
        'preview' => ':lang/cases/:slug',
        'fields' => array(
            ['form' => 'text', 'type' => 'intro', 'title' => 'Intro', 'editor' => 'editor-small'],
            ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
        ),
        'links' => array(
            'PRODUCTS' => array(
                //'type' => 'single',
                'type' => 'multiple',
                'description' => 'Used products',
                'overview_filter' => true,
                'input_type' => 'chosen',//chosen or ''
                'add_item' => false,
            ),
        ),
        'field_validation' => array(
            'description' => 'required',
            'my_content.seo_title' => 'required',
            'my_content.title' => 'required',
        ),
        'field_validation_nicenames' => array(
            'description' => 'description',
            'my_content.seo_title' => 'seo title',
            'my_content.title' => 'title',
        ),
    ),
    'PRODUCTS' => array(
        'description' => 'Products',
        'sortable' => true,
        'sort_by' => 'sort',
        'preview' => ':lang/products/:slug',
        'fields' => array(
            ['form' => 'text', 'type' => 'intro', 'title' => 'Intro'],
            ['form' => 'text', 'type' => 'tinyeditor', 'title' => 'Tiny editor', 'editor' => 'editor--tiny'],
            ['form' => 'select', 'type' => 'select_candy', 'title' => 'Select Candy', 'options' => array('twix' => 'Twix', 'mars' => 'Mars', 'gummybear' => 'Gummybear')],
            ['form' => 'file', 'type' => 'brochure_pdf', 'title' => 'Brochure.pdf'],
            ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
            ['form' => 'images', 'type' => 'image', 'title' => 'Images', 'amount' => 5],
            ['form' => 'files', 'type' => 'file', 'title' => 'Files', 'amount' => 5],
        ),
        'blocks' => array(
            'quote' => [
                'description' => 'Quote',
                'amount' => 1, //infinite when null
                'fields' => [
                    ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                    ['type' => 'author', 'form' => 'text', 'title' => 'Author'],
                    ['type' => 'image_1', 'form' => 'image', 'title' => 'Image 1'],
                ]
            ],
            'case' => [
                'description' => 'Case',
                'amount' => null, //infinite when null
                'fields' => [
                    ['type' => 'intro', 'form' => 'text', 'title' => 'Intro', 'editor' => 'editor--tiny'],
                ],
                'links' => [
                    'CASES' => [
                        'description' => 'Featured case',
                        //'type' => 'single',
                        'type' => 'multiple',
                        'title' => 'Case',
                        'input_type' => 'chosen',//chosen or ''
                        'add_item' => false,
                    ],
                ]
            ],
        ),
        'links' => array(
            'CATEGORIES' => array(
                'type' => 'single',
                //'type'=>'multiple',
                'description' => 'Categories',
                'overview_filter' => true,
                'input_type' => '',//chosen or ''
                'add_item' => false,
            ),
            'CATEGORIESTEST' => array(
                //'type' => 'single',
                'type' => 'multiple',
                'description' => 'Categories test module',
                'overview_filter' => true,
                'input_type' => '',//chosen or ''
                'add_item' => false,
            ),
        ),
        'field_validation' => array(
            'description' => 'required',
            'my_content.title' => 'required',
        ),
        'field_validation_nicenames' => array(
            'description' => 'description',
            'my_content.title' => 'title',
        ),
    ),
    'CATEGORIES' => array(
        'description' => 'Categories',
        'sortable' => true,
        'sort_by' => 'sort',
        'preview' => ':lang/categories/:slug',
        'fields' => array(
            ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
        ),
        'links' => array(),
        'field_validation' => array(
            'description' => 'required',
            'my_content.title' => 'required',
        ),
        'field_validation_nicenames' => array(
            'description' => 'description',
            'my_content.title' => 'title',
        ),
    ),
    'CATEGORIESTEST' => array(
        'description' => 'Categories test',
        'sortable' => true,
        'sort_by' => 'sort',
        'preview' => ':lang/CATEGORIESTEST/:slug',
        'fields' => array(
            ['form' => 'image', 'type' => 'image_header', 'title' => 'Header Image'],
        ),
        'links' => array(),
        'field_validation' => array(
            'description' => 'required',
            'my_content.title' => 'required',
        ),
        'field_validation_nicenames' => array(
            'description' => 'description',
            'my_content.title' => 'title',
        ),
    ),
    'EXPORT'    => [
        'description'   => 'Export data',
        'nav_mode'      => 'route',//url or route. if empty link will not be used
        'url'       => '',
        'route'     => 'icontrol.export',
        'params'    => [],
        'always_visible_for_admin' => true,//if false, only permission will be checked
    ]
);
