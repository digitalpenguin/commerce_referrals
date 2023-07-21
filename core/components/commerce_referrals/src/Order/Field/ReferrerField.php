<?php

namespace DigitalPenguin\Referrals\Order\Field;

use modmore\Commerce\Exceptions\ViewException;
use modmore\Commerce\Order\Field\AbstractField;

class ReferrerField extends AbstractField
{
    protected string $json = '';

    public function __construct(\Commerce $commerce, $name, $value)
    {
        parent::__construct($commerce, $name, $value);
        $this->json = $value;
    }

    public function renderForAdmin(): string
    {
        $values = json_decode($this->json, true);
        try {
            return $this->commerce->view()->render('admin/orders/fields/referrer.twig', [
                'company' => $values['name'],
                'contact_name' => $values['contact_person'],
                'email' => $values['email'],
                'phone' => $values['phone'],
                'website' => $values['website'],
            ]);
        } catch (ViewException $e) {
            return $e->getMessage();
        }
    }

    public function renderForCustomer()
    {
        return $this->renderForAdmin();
    }
}