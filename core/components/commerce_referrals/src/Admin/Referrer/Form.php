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
 * @property $record \CommerceReferralsReferral
 */
class Form extends FormWidget
{
    protected $classKey = 'CommerceReferralsReferral';
    public $key = 'referrals-form';
    public $title = '';

    public function getFields(array $options = array())
    {
        $fields = [];

        $fields[] = new TextField($this->commerce, [
            'name' => 'code',
            'label' => $this->adapter->lexicon('commerce.code'),
            'description' => 'The code the customer has to enter.',
            'validation' => [
                new Required(),
                new Length(3, 190),
            ]
        ]);


        $fields[] = new SectionField($this->commerce, [
            'label' => 'Discount'
        ]);

        $fields[] = new NumberField($this->commerce, [
            'name' => 'discount',
            'label' => $this->adapter->lexicon('commerce.discount'),
            'input_class' => 'commerce-field-currency',
        ]);

        $fields[] = new NumberField($this->commerce, [
            'name' => 'discount_percentage',
            'label' => $this->adapter->lexicon('commerce.discount_percentage'),
            'description' => 'Percentage with up to 4 decimals (e.g. 2.5 for a 2,5% discount)',
        ]);

        $fields[] = new SectionField($this->commerce, [
            'label' => 'Availability'
        ]);

        $fields[] = new CheckboxField($this->commerce, [
            'name' => 'active',
            'label' => $this->adapter->lexicon('commerce.active'),
            'value' => true,
        ]);

        $fields[] = new NumberField($this->commerce, [
            'name' => 'max_uses',
            'label' => $this->adapter->lexicon('commerce.max_uses'),
        ]);
        $fields[] = new SelectMultipleField($this->commerce, [
            'name' => 'products',
            'label' => $this->adapter->lexicon('commerce.products'),
            'optionsClass' => 'comProduct',
            'optionsCondition' => ['removed' => false]
        ]);

        $fields[] = new DateTimeField($this->commerce, [
            'name' => 'available_from',
            'label' => $this->adapter->lexicon('commerce.available_from'),
        ]);

        $fields[] = new DateTimeField($this->commerce, [
            'name' => 'available_until',
            'label' => $this->adapter->lexicon('commerce.available_until'),
        ]);

        $fields[] = new NumberField($this->commerce, [
            'name' => 'minimum_order_total',
            'label' => $this->adapter->lexicon('commerce.minimum_order_total'),
            'input_class' => 'commerce-field-currency',
        ]);

        $fields[] = new NumberField($this->commerce, [
            'name' => 'maximum_order_total',
            'label' => $this->adapter->lexicon('commerce.maximum_order_total'),
            'input_class' => 'commerce-field-currency',
        ]);

        return $fields;
    }

    public function getFormAction(array $options = array())
    {
        if ($this->record->get('id')) {
            return $this->adapter->makeAdminUrl('coupons/update', ['id' => $this->record->get('id')]);
        }
        return $this->adapter->makeAdminUrl('coupons/create');
    }

    public function newRecordCreated()
    {
        $this->record->set('created_on', time());
        $this->record->set('created_by', $this->adapter->getUser()->get('id'));
    }
}