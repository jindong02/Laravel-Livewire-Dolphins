<?php

use App\Models\MinuteTemplate;
use Carbon\Carbon;

/**
 * Format amount with decimal place
 *
 */
function formatAmount($amount, $currency = '₱') {
    return $currency . ' ' . number_format($amount, 2, '.', ',');
}

/**
 * Format Date & Time Display
 */
function formatDateTime($date, $format = 'm/d/Y h:i A') {
    return Carbon::parse($date)->format($format);
}

function createUiFromMinuteTemplate($key, $value = null, $errors = null, $attribute = [])
{
    $template = MinuteTemplate::where('key', $key)->firstOrFail();

    $errorAlert = '';
    $errorInvalidClass = '';

    if ($errors && $errors->has($key)) {
        $errorInvalidClass = 'is-invalid';
        $errorAlert = "<div class='invalid-feedback'>" . $errors->first('email') . "</div>";
    }

    switch ($template->data_type) {
        case 'boolean':
            $value = (bool) $value;
            $isChecked = $value ? 'checked' : '';
            return
            "<div class='form-check form-switch minute-template-group'>
                <input class='form-check-input minute-template-checkbox {$errorInvalidClass}' type='checkbox' role='switch' name='options[{$key}]' id='{$key}' value='1' {$isChecked}>
                <label class='form-check-label minute-template-label' for='{$key}'>{$template->label}</label>
                {$errorAlert}
            </div>";
            break;

        case 'file':
            $downloadLink = '';
            if ($value) {
                $downloadLink =
                "<div class='col-md-6 col-sm-12 mt-auto'>
                    <a class='btn btn-outline-primary'>Download</a>
                </div>";
            }

            return
            "<div class='row'>
                <div class='col-md-6 col-sm-12'>
                    <label for='{$key}' class='form-label minute-template-label'>{$template->label}</label>
                    <input type='file' class='form-control minute-template-file {$errorInvalidClass}' name='options[{$key}]' id='{$key}'>
                    {$errorAlert}
                </div>
                {$downloadLink}
            </div>";
            break;

        default:
            throw new Exception("Invalid Template Data Type ({$key})" , 1);

            break;
    }
}
