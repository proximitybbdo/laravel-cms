<?php

namespace BBDO\Cms\Http\Requests;

use Config;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use BBDO\Cms\Domain\Item as ItemDomain;
use BBDO\Cms\Models\Item;
use Route;

class StoreItem extends FormRequest
{
    private $mandatoryFields = [
        'my_content.seo_title',
        'my_content.name',
        'my_content.slug'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = Config::get('cms.' . $this->module_type . '.field_validation');
        $hideMandatoryFields = Config('admin.' . strtoupper($this->module_type) . '.hide_mandatory_fields');

        // no validation required in case mandatory fields are actually hidden
        if ($hideMandatoryFields) {
            foreach ($this->mandatoryFields as $mandatoryField) {
                unset($rules[$mandatoryField]);
            }
        }

        return $rules;
    }

    /**
     * Custom validation
     *
     * @param Validator $validator
     */
    public function withValidator(Validator $validator)
    {
        $validator->after(function (Validator $validator) {
            $data = $validator->getData();

            if (key_exists('slug', $data['my_content'])) {
                $itemDomain = new ItemDomain();

                $item = new Item();

                if ($data['id']) {
                    $item = $itemDomain->get_admin($data['id'], $data['lang']);
                }

                if ($itemDomain->count_slug($data['my_content']['slug'], $data['lang'], $item->id, $this->module_type) > 0) {
                    $validator->errors()->add('my_content.slug', 'Slug already exists, please change the SEO Title!');
                }
            }
        });
    }
}
