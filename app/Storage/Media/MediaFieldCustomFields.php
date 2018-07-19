<?php namespace App\Storage\Media;

use \Form;
use \Html;

class MediaFieldCustomFields {

    public function mediaUpload($crud_field) {
        $label        = ($crud_field->getOption('label') ? '<label>' . $crud_field->getOption('label') . '</label>' : '');
        $options      = $crud_field->getOption('field-attr');
        $name         = $crud_field->getOption('name');
        $value        = $crud_field->getOption('value');
        $isSingle     = $crud_field->getOption('is_single', false);
        $accepts      = ($crud_field->getOption('accepts') ? $crud_field->getOption('accepts') : 'image/*');
        $footModalRth = ($crud_field->getOption('modfootrth') ? $crud_field->getOption('modfootrth') : '');
        $btnTxt       = $crud_field->getOption('btn_txt', "Add Media");
        $isDisable    = (isset($options['disabled']) && $options['disabled'] ? $options['disabled'] : false);

        if (!isset($options['id'])) {
            $options['id'] = $name;
        }

        if (!isset($options['class'])) {
            $options['class'] = '';
        }

        $options['class'] .= ' form-control';
        $val = Form::getValueAttribute($name, $value);

        if ($isSingle) {
            $value = $val;
            if (is_array($value)) {
                $value = ($val ? implode(',', $val) : null);
            }
        } else {
            $value = ($val ? implode(',', $val) : null);
        }

        $btn = <<<HTML
	    <div class="form-group">
	    	$label
			<button type="button" class="btn btn-primary btn-block media-upload" data-label="$btnTxt">$btnTxt</button>
		</div>
HTML;
        if (!$isDisable) {
            $btnRow = HTML::bs_row(HTML::bs_col($btn, ['xs' => 6, 'sm' => 6, 'md' => 3], ['xs' => 3, 'sm' => 3, 'md' => 0]));
        } else {
            $btnRow = '';
        }

        $media = <<<HTML
	    <ul class="media-unorderlist"></ul>
HTML;

        $mediaRow = HTML::bs_row(HTML::bs_col($media));

        return <<<HTML
		<div class="media-field disable-$isDisable" id="media-field-$name" data-field-name="$name" data-field-accept="$accepts" data-field-value="$value" data-modal-foot-right="$footModalRth" data-is-single="$isSingle">
			$mediaRow
			$btnRow
		</div>
HTML;
    }
    public function mediaCsvUpload($crud_field) {

        $label        = ($crud_field->getOption('label') ? '<label>' . $crud_field->getOption('label') . '</label>' : '');
        $options      = $crud_field->getOption('field-attr');
        $name         = $crud_field->getOption('name');
        $value        = $crud_field->getOption('value');
        $isSingle     = $crud_field->getOption('is_single', true);
        $accepts      = ($crud_field->getOption('accepts') ? $crud_field->getOption('accepts') : 'csv/*');
        $footModalRth = ($crud_field->getOption('modfootrth') ? $crud_field->getOption('modfootrth') : '');
        $btnTxt       = $crud_field->getOption('btn_txt', "Add Media");
        $isDisable    = (isset($options['disabled']) && $options['disabled'] ? $options['disabled'] : false);

        if (!isset($options['id'])) {
            $options['id'] = $name;
        }

        if (!isset($options['class'])) {
            $options['class'] = '';
        }

        $options['class'] .= ' form-control';
        $val = Form::getValueAttribute($name, $value);

        if ($isSingle) {
            $value = $val;
            if (is_array($value)) {
                $value = ($val ? implode(',', $val) : null);
            }
        } else {
            $value = ($val ? implode(',', $val) : null);
        }

        $btn = <<<HTML
        <div class="form-group">
            $label
            <button type="button" class="btn btn-primary btn-block media-upload" data-label="$btnTxt">$btnTxt</button>
        </div>
HTML;
        if (!$isDisable) {
            $btnRow = HTML::bs_row(HTML::bs_col($btn, ['xs' => 6, 'sm' => 6, 'md' => 3], ['xs' => 3, 'sm' => 3, 'md' => 0]));
        } else {
            $btnRow = '';
        }

        $media = <<<HTML
        <ul class="media-unorderlist"></ul>
HTML;

        $mediaRow = HTML::bs_row(HTML::bs_col($media));

        return <<<HTML
        <div class="media-field disable-$isDisable" id="media-field-$name" data-field-name="$name" data-field-accept="$accepts" data-field-value="$value" data-modal-foot-right="$footModalRth" data-is-single="$isSingle">
            $mediaRow
            $btnRow
        </div>
HTML;
    }

    public function mediaUrl($crud_field) {
        // dd($crud_field);
        // $label        = ($crud_field->getOption('label') ? '<label>' . $crud_field->getOption('label') . '</label>' : '');
        // $options      = $crud_field->getOption('field-attr');
        // $name         = $crud_field->getOption('name');
        // $value        = $crud_field->getOption('value');

        //  if(!isset($options['id'])){
        //        $options['id'] = $name;
        //    }

        //    if(!isset($options['class'])){
        //        $options['class'] = '';
        //    }

        //    $options['class'] .= ' form-control';

        //    '<img src="'.$value.'" class="img img-thumbnail">';
        return view('admin.crud.form.custom-fields.image', compact('crud_field'));

    }
//         $label        = ($crud_field->getOption('label') ? '<label>' . $crud_field->getOption('label') . '</label>' : '');
    //         $options      = $crud_field->getOption('field-attr');
    //         $name         = $crud_field->getOption('name');
    //         $value        = $crud_field->getOption('value');
    //         $isSingle     = $crud_field->getOption('is_single', false);
    //         // $accepts      = ($crud_field->getOption('accepts') ? $crud_field->getOption('accepts') : 'image/*');
    //         $footModalRth = ($crud_field->getOption('modfootrth') ? $crud_field->getOption('modfootrth') : '');
    //         // $btnTxt       = $crud_field->getOption('btn_txt', "Add Media");
    //         $isDisable =  false;//(isset($options['disabled']) && $options['disabled'] ? $options['disabled'] : false);

//         if(!isset($options['id'])){
    //             $options['id'] = $name;
    //         }

//         if(!isset($options['class'])){
    //             $options['class'] = '';
    //         }

//         $options['class'] .= ' form-control';
    //         // $val = Form::getValueAttribute($name, $value);

//         // if($isSingle){
    //         //     $value = $val;
    //         //     if(is_array($value)){
    //         //         $value = ($val ? implode(',', $val) : null);
    //         //     }
    //         // }else{
    //            //     $value = ($val ? implode(',', $val) : null);
    //         // }

// //         $btn = <<<HTML
    // //         <div class="form-group">
    // //             $label
    // //             <button type="button" class="btn btn-primary btn-block media-upload" data-label="$btnTxt">$btnTxt</button>
    // //         </div>
    // // HTML;
    //         // if(!$isDisable){
    //         //     $btnRow = HTML::bs_row(HTML::bs_col($btn, ['xs' => 6, 'sm' => 6, 'md' => 3], ['xs' => 3, 'sm' => 3, 'md' => 0]));
    //         // }else{
    //         //     $btnRow = '';
    //         // }

//         $media = <<<HTML
    //         <ul class="media-unorderlist"></ul>
    // HTML;

//         $mediaRow = HTML::bs_row(HTML::bs_col($media));

//         return <<<HTML
    //         <div class="media-field disable-$isDisable" id="media-field-$name" data-field-name="$name"   data-field-value="$value" data-modal-foot-right="$footModalRth" data-is-single="$isSingle">
    //             $mediaRow

//         </div>
    // HTML;

//     }
}