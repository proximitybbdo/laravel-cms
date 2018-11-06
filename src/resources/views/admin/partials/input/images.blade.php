<div class="form-group multiple_container">
 <label class="control-label" for="{{ $field }}">
    {{ $title }} <a class="multiple_control_add" data-type='{{ $type }}' data-amount='{{$amount}}' ><span class="fa fa-plus-square"></span></a>
 </label>
@for ($i = 0; $i < $amount; $i++)
@include( 'bbdocms::admin.partials.input.image',inputArray($field_arr,'content',$model,$i))
@endfor
</div>
