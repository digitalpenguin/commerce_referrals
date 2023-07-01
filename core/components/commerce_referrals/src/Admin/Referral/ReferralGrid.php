<?php
namespace DigitalPenguin\Referrals\Admin\Referral;

use modmore\Commerce\Admin\Widgets\GridWidget;
use modmore\Commerce\Gateways\Helpers\GatewayHelper;

class ReferralGrid extends GridWidget {
    public $key = 'referrals-grid';
    public $defaultSort = 'referred_on';
    public $defaultSortDir = 'DESC';

    public function getItems(array $options = [])
    {
        $items = [];

        $c = $this->adapter->newQuery(\CommerceReferralsReferral::class);
        $c->leftJoin('CommerceReferralsReferrer','Referrer',[
            'CommerceReferralsReferral.referrer_id = Referrer.id'
        ]);
        $c->leftJoin(\comOrder::class, 'Order', [
            'Order.id = CommerceReferralsReferral.order',
        ]);
        $c->leftJoin(\comOrderAddress::class, 'OrderAddress', [
            'OrderAddress.order = Order.id',
        ]);
        $count = $this->adapter->getCount(\CommerceReferralsReferral::class, $c);
        $this->setTotalCount($count);
        $c->sortby($this->defaultSort,$this->defaultSortDir);
        $c->limit($options['limit'], $options['start']);
        $c->select($this->adapter->getSelectColumns(
            \CommerceReferralsReferrer::class,
            'Referrer',
            '',
            ['name'],
        ));
        $c->select($this->adapter->getSelectColumns(
            \comOrderAddress::class,
            'OrderAddress',
            '',
            ['fullname', 'firstname', 'lastname'],
        ));
        $c->select($this->adapter->getSelectColumns(
            \CommerceReferralsReferral::class,
            'CommerceReferralsReferral',
        ));

        if (array_key_exists('search_by_referrer', $options)) {
            if ($options['search_by_referrer']) {
                $c->where([
                    'Referrer.name:LIKE' => '%' . $options['search_by_referrer'] . '%'
                ]);
            }
        }
        $collection = $this->adapter->getCollection(\CommerceReferralsReferral::class, $c);

        foreach ($collection as $object) {
            $items[] = $this->prepareItem($object->toArray());
        }

        return $items;
    }

    public function getColumns(array $options = [])
    {
        return [
            /*[
                'name' => 'id',
                'title' => 'ID',
                'primary' => true,
                'sortable'=>true,
            ],*/
            [
                'name' => 'referrer',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.referrer'),
                'sortable' => true,
            ],
            [
                'name' => 'referred_on',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.when'),
                'sortable' => true,
            ],
            [
                'name' => 'customer',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.customer'),
                'sortable' => true,
            ],
            [
                'name' => 'amount',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.amount'),
                'sortable' => true,
            ],
            [
                'name' => 'link',
                'title' => $this->adapter->lexicon('commerce_referrals.referral.order'),
                'sortable' => true,
                'raw' => true,
            ],
        ];
    }

    public function getTopToolbar(array $options = array())
    {
        $toolbar = [];

        $toolbar[] = [
            'name' => 'search_by_referrer',
            'title' => $this->adapter->lexicon('commerce_referrals.search_by_referrer'),
            'type' => 'textfield',
            'value' => array_key_exists('search_by_referrer', $options) ? (int)$options['search_by_referrer'] : '',
            'position' => 'top',
            'width' => 'four wide',
        ];


        $toolbar[] = [
            'name' => 'limit',
            'title' => $this->adapter->lexicon('commerce.limit'),
            'type' => 'textfield',
            'value' => (int)$options['limit'],
            'position' => 'bottom',
            'width' => 'two wide',
        ];

        return $toolbar;
    }

    public function prepareItem($item)
    {
        $item['actions'] = [];

        if ($item['order']) {
            $orderId = $item['order'];
            $order = $this->adapter->getObject('comOrder',[
                'id'    =>  $orderId
            ]);
            if ($order) {
                $order = $order->toArray();
                $item['amount'] = $order['total_formatted'];
                $item['link'] = '<a href="?namespace=commerce&a=index&ca=order&order=' . $item['order'] . '">' . $this->adapter->lexicon('commerce_referrals.referral.view_order_details') . '</a>';

                GatewayHelper::normalizeNames($item['firstname'], $item['lastname'], $item['fullname']);
                $item['customer'] = $item['fullname'];
            }
        }



        if ($item['referred_on']) {
            $item['referred_on'] = date('H:i A - dS M Y', $item['referred_on']);
        }

        if ($item['referrer_id']) {
            $referrer = $this->adapter->getObject('CommerceReferralsReferrer',[
                'id'    => $item['referrer_id']
            ]);
            if ($referrer) {
                $item['referrer'] = $referrer->get('name');
            }
        }


        /*
        $editUrl = $this->adapter->makeAdminUrl('referrals/update', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($editUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referral.edit'))
            ->setIcon('icon-edit');
        $deleteUrl = $this->adapter->makeAdminUrl('referrals/delete', ['id' => $item['id']]);
        $item['actions'][] = (new Action())
            ->setUrl($deleteUrl)
            ->setTitle($this->adapter->lexicon('commerce_referrals.referral.delete'))
            ->setIcon('icon-trash');
        */


        return $item;
    }
}
