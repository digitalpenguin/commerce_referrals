<?php

namespace DigitalPenguin\Referrals\Admin\Referrer;

use modmore\Commerce\Admin\Widgets\Form\NumberField;
use modmore\Commerce\Admin\Widgets\Form\Validation\Required;
use DigitalPenguin\Referrals\Admin\Referrer\Validate\Unique;
use modmore\Commerce\Admin\Widgets\FormWidget;
use modmore\Commerce\Admin\Widgets\Form\CheckboxField;
use modmore\Commerce\Admin\Widgets\Form\DateTimeField;
use modmore\Commerce\Admin\Widgets\Form\SectionField;
use modmore\Commerce\Admin\Widgets\Form\SelectMultipleField;
use modmore\Commerce\Admin\Widgets\Form\TextField;
use modmore\Commerce\Admin\Widgets\Form\Validation\Length;
use modmore\Commerce\Admin\Widgets\Form\Validation\Regex;
use modmore\Commerce\Admin\Widgets\Form\Validation\Rule;


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
    protected $referrerId = 0;

    public function getFields(array $options = array())
    {
        $fields = [];

        // Check if update.
        if($this->record->get('id')) {
            $this->referrerId = $this->record->get('id');
        }

        $fields[] = new TextField($this->commerce, [
            'name' => 'name',
            'label' => $this->adapter->lexicon('commerce_referrals.name'),
            'description' => 'Your referrer\'s name.',
            'validation' => [
                new Required(),
                new Length(3, 190),
            ]
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'token',
            'label' => $this->adapter->lexicon('commerce_referrals.token'),
            'description' => $this->adapter->lexicon('commerce_referrals.token_desc'),
            'validation' => [
                new Required(),
                new Unique($this->commerce,$this->classKey,'token',$this->referrerId),
            ]
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'contact_person',
            'label' => $this->adapter->lexicon('commerce_referrals.contact_person'),
            'description' => 'Name of the person you\'re in contact with at this company.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'email',
            'label' => $this->adapter->lexicon('commerce_referrals.email'),
            'description' => 'Your referrer\'s email address.',
            'validation' => [
                new Required(),
                new Length(3, 190),
                // This is regex that checks the email address is a valid format.
                new Regex(' 	
/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD','Please enter a valid email address.'),
            ]
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'phone',
            'label' => $this->adapter->lexicon('commerce_referrals.phone'),
            'description' => 'Contact phone number.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'website',
            'label' => $this->adapter->lexicon('commerce_referrals.website'),
            'description' => 'Company website.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'address1',
            'label' => $this->adapter->lexicon('commerce_referrals.address1'),
            'description' => 'First line of address.',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'address2',
            'label' => $this->adapter->lexicon('commerce_referrals.address2'),
            'description' => 'Second line of address',
        ]);

        $fields[] = new TextField($this->commerce, [
            'name' => 'country',
            'label' => $this->adapter->lexicon('commerce_referrals.country'),
            'description' => 'Select a country.',
        ]);

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