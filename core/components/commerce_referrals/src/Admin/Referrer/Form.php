<?php

namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Widgets\Form\NumberField;
use modmore\Commerce\Admin\Widgets\Form\Validation\Required;
use modmore\Commerce\Admin\Widgets\FormWidget;
use modmore\Commerce\Admin\Widgets\Form\CheckboxField;
use modmore\Commerce\Admin\Widgets\Form\DateTimeField;
use modmore\Commerce\Admin\Widgets\Form\SectionField;
use modmore\Commerce\Admin\Widgets\Form\SelectMultipleField;
use modmore\Commerce\Admin\Widgets\Form\TextField;
use modmore\Commerce\Admin\Widgets\Form\Validation\Length;

/**
 * Class Form
 * @package DigitalPenguin\Referrals
 *
 * @property $record \CommerceReferralsReferrer
 */
class Form extends FormWidget
{
    protected $classKey = 'CommerceReferralsReferrer';
    public $key = 'referrer-form';
    public $title = '';

    public function getFields(array $options = array())
    {
        $fields = [];

        $fields[] = new TextField($this->commerce, [
            'name' => 'name',
            'label' => $this->adapter->lexicon('commerce_referrals.name'),
            'description' => 'Your referrer\'s name.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'token',
            'label' => $this->adapter->lexicon('commerce_referrals.token'),
            'description' => 'This is the token add to the end of a product URL that your partner company will use to refer customers.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'contact_person',
            'label' => $this->adapter->lexicon('commerce_referrals.contact_person'),
            'description' => 'Name of the person you\'re in contact with at this company.',
        ]);
        foreach($fields as $field) {
            $this->adapter->log(1,print_r($field->getHTML(),true));
        }

        //return array_merge($fields, $this->record->getModelFields());
        return $fields;
    }

    public function getFormAction(array $options = array())
    {
        if ($this->record->get('id')) {
            return $this->adapter->makeAdminUrl('referrers/update', ['id' => $this->record->get('id')]);
        }
        return $this->adapter->makeAdminUrl('referrers/create');
    }


}